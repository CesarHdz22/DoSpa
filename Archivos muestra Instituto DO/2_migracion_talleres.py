import pandas as pd
import mysql.connector
import re
import numpy as np

db_config = {
    'host': 'localhost', 'user': 'root', 'password': '', 'database': 'instituto_dos'
}

def limpieza_para_comparar(texto):
    """Limpia y pone en UPPER solo para la lógica de comparación"""
    if texto is None or pd.isna(texto): return ""
    temp = re.sub(r'\(https?://\S+\)', '', str(texto))
    temp = re.sub(r'\(.*?\)', '', temp) # Quita (GDL), (Mat), etc.
    temp = re.sub(r'^[\d.]+\s+', '', temp) # Quita fechas
    temp = temp.upper().strip()
    temp = re.sub(r'^TALLER\s+', '', temp)
    temp = re.sub(r'[^A-ZÁÉÍÓÚÑ\s]', '', temp)
    return ' '.join(temp.split()).strip()

def limpiar_monto(valor):
    if valor is None or pd.isna(valor) or valor == '': return 0.0
    res = re.sub(r'[^\d.]', '', str(valor).replace(',', ''))
    return float(res) if res else 0.0

def migrar_talleres_estetico():
    try:
        conn = mysql.connector.connect(**db_config)
        cursor = conn.cursor(buffered=True)
       
        
        path = 'c:/xampp/htdocs/DO spa/Archivos muestra Instituto DO/BD Control Talleres Muestra.xlsx'
        df = pd.read_excel(path)

        # Diccionario para controlar: { NOMBRE_UPPER: [ID, NOMBRE_TITLE, COSTO_MINIMO] }
        talleres_dict = {}
        id_consecutivo = 1

        print("🚀 Normalizando catálogo con Capitalización y Costos Mínimos...")

        for _, row in df.iterrows():
            nombre_sucio = row['Nombre del taller']
            if pd.isna(nombre_sucio): continue

            nombre_comp = limpieza_para_comparar(nombre_sucio)
            costo_actual = limpiar_monto(row['Precio del taller'])
            if not nombre_comp: continue

            # Lógica de búsqueda en nuestro diccionario (Array mejorado)
            encontrado = False
            for nombre_reg in list(talleres_dict.keys()):
                if nombre_reg in nombre_comp or nombre_comp in nombre_reg:
                    # Si ya existe, actualizamos el costo si el nuevo es más bajo
                    if costo_actual > 0 and costo_actual < talleres_dict[nombre_reg]['costo_min']:
                        talleres_dict[nombre_reg]['costo_min'] = costo_actual
                    encontrado = True
                    break
            
            if not encontrado:
                # Es un taller nuevo: lo guardamos en el diccionario
                # .title() pone la primera letra de cada palabra en mayúscula
                talleres_dict[nombre_comp] = {
                    'id': id_consecutivo,
                    'nombre_estetico': nombre_comp.title(),
                    'costo_min': costo_actual
                }
                id_consecutivo += 1

        # Una vez procesado todo el Excel, insertamos los resultados finales
        for key in talleres_dict:
            t = talleres_dict[key]
            cursor.execute("""
                INSERT INTO talleres (id_taller, nombre, id_sucursal, costo_base, precio_preferencial, status)
                VALUES (%s, %s, %s, %s, %s, %s)
            """, (t['id'], t['nombre_estetico'], 1, t['costo_min'], t['costo_min'], 'Activo'))

        conn.commit()
        print(f"✅ Catálogo finalizado con {len(talleres_dict)} talleres únicos.")
        print(f"💡 Ejemplo: 'PEELING ACIDOS' se insertó como 'Peeling Ácidos'")

    except Exception as e:
        print(f"❌ Error: {e}")
        conn.rollback()
    finally:
        cursor.close()
        conn.close()

if __name__ == "__main__":
    migrar_talleres_estetico()