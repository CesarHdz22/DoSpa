import pandas as pd
import mysql.connector
import re
import numpy as np

db_config = {
    'host': 'localhost', 'user': 'root', 'password': '', 'database': 'instituto_dos'
}

def limpieza_busqueda_exacta(texto):
    if texto is None or pd.isna(texto): return ""
    temp = re.sub(r'\(https?://\S+\)', '', str(texto))
    temp = re.sub(r'\(.*?\)', '', temp)
    temp = re.sub(r'^[\d.]+\s+', '', temp)
    temp = temp.upper().strip()
    temp = re.sub(r'^TALLER\s+', '', temp)
    temp = re.sub(r'[^A-ZÁÉÍÓÚÑ\s]', '', temp)
    return ' '.join(temp.split()).strip()

def formatear_fecha(fecha_raw):
    if fecha_raw is None or pd.isna(fecha_raw): return None
    try:
        fecha_limpia = str(fecha_raw).split(' ')[0]
        return pd.to_datetime(fecha_limpia).strftime('%Y-%m-%d')
    except:
        return None

def migrar_agenda_consecutiva():
    try:
        conn = mysql.connector.connect(**db_config)
        cursor = conn.cursor(dictionary=True)
        
        # 1. Mapa de Talleres desde la BD
        cursor.execute("SELECT id_taller, nombre FROM talleres")
        mapa_talleres = {row['nombre'].upper(): row['id_taller'] for row in cursor.fetchall()}

        # 2. Limpieza total para reconstruir con IDs nuevos
        cursor.execute("SET FOREIGN_KEY_CHECKS = 0;")
        cursor.execute("TRUNCATE TABLE agenda")
        cursor.execute("SET FOREIGN_KEY_CHECKS = 1;")
        
        df = pd.read_excel('c:/xampp/htdocs/DO spa/Archivos muestra Instituto DO/BD Control Talleres Muestra.xlsx')

        # Set para llevar control de qué (Taller + Fecha) ya insertamos
        eventos_procesados = set()
        id_consecutivo = 1 

        print("📅 Creando agenda con IDs perfectos...")

        for _, row in df.iterrows():
            nombre_excel = row['Nombre del taller']
            fecha_sql = formatear_fecha(row['Fecha del taller'])
            
            if not nombre_excel or not fecha_sql: continue

            nombre_limpio = limpieza_busqueda_exacta(nombre_excel)
            id_taller = None

            for nombre_bd, id_reg in mapa_talleres.items():
                if nombre_bd in nombre_limpio or nombre_limpio in nombre_bd:
                    id_taller = id_reg
                    break
            
            if id_taller:
                # CREAMOS UNA LLAVE ÚNICA PARA COMPARAR EN EL ARRAY
                llave_evento = f"{id_taller}_{fecha_sql}"

                if llave_evento not in eventos_procesados:
                    # Insertamos con el ID manual
                    cursor.execute("""
                        INSERT INTO agenda (id_agenda, id_taller, fecha, id_sucursal, ubicacion, cant_inscritas)
                        VALUES (%s, %s, %s, %s, %s, %s)
                    """, (id_consecutivo, id_taller, fecha_sql, 1, 'GUADALAJARA', row['Alumnas inscritas'] or 0))
                    
                    eventos_procesados.add(llave_evento)
                    id_consecutivo += 1

        conn.commit()
        print(f"✅ Agenda terminada. IDs del 1 al {id_consecutivo - 1} sin saltos.")

    except Exception as e:
        print(f"❌ Error: {e}")
        conn.rollback()
    finally:
        cursor.close()
        conn.close()

if __name__ == "__main__":
    migrar_agenda_consecutiva()