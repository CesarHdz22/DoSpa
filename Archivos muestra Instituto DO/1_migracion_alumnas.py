import pandas as pd
import mysql.connector
import numpy as np

db_config = {
    'host': 'localhost',
    'user': 'root',
    'password': '', 
    'database': 'instituto_dos'
}

def limpiar_telefono(valor):
    """Quita el .0 y convierte a texto limpio"""
    if valor is None or pd.isna(valor) or valor == '':
        return None # Permitimos que sea NULL en la base de datos
    
    # Convertimos a string, quitamos el .0 si existe y espacios
    tel = str(valor).replace('.0', '').strip()
    return tel if tel != 'None' else None

def migrar_alumnas_vaciado():
    try:
        conn = mysql.connector.connect(**db_config)
        cursor = conn.cursor(buffered=True)
        
        path = 'c:/xampp/htdocs/DO spa/Archivos muestra Instituto DO/BD - Alumnas Instituto Muestra.xlsx'
        # Leemos todo como texto (dtype=str) para evitar que Pandas invente decimales
        df = pd.read_excel(path, dtype=str)

        print("👤 Iniciando vaciado total de Alumnas...")
        
        conteo = 0
        for _, row in df.iterrows():
            nombre = row['Nombre completo']
            # Aplicamos la limpieza para quitar el .0 y manejar nulos
            telefono = limpiar_telefono(row['Telefono'])

            # Si hay nombre, se inserta (aunque no haya teléfono)
            if pd.notna(nombre) and str(nombre).strip() != '':
                cursor.execute("""
                    INSERT INTO alumnas (nombre, telefono)
                    VALUES (%s, %s)
                """, (str(nombre).strip(), telefono))
                conteo += 1

        conn.commit()
        print(f"✅ ¡Vaciado completo! Se ingresaron {conteo} alumnas.")
        print(f"💡 La alumna 47 y las demás sin teléfono ahora están en la base de datos.")

    except Exception as e:
        print(f"❌ Error: {e}")
        if 'conn' in locals(): conn.rollback()
    finally:
        if 'cursor' in locals(): cursor.close()
        if 'conn' in locals(): conn.close()

if __name__ == "__main__":
    migrar_alumnas_vaciado()