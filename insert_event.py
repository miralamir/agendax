import os
import re
import mysql.connector
from datetime import datetime

def get_env_vars(path):
    env_vars = {}
    with open(path, 'r') as file:
        for line in file:
            line = line.strip()
            if line and not line.startswith('#'):
                match = re.match(r'([^=]+)=(.*)', line)
                if match:
                    key, value = match.groups()
                    # Strip quotes if present
                    if (value.startswith('"') and value.endswith('"')) or \
                       (value.startswith("'") and value.endswith("'")):
                        value = value[1:-1]
                    env_vars[key.strip()] = value.strip()
    return env_vars

def insert_event():
    try:
        # --- 1. Leer credenciales del .env ---
        print("Leyendo credenciales de la base de datos...")
        env_path = '.env'
        if not os.path.exists(env_path):
            print(f"Error: El archivo {env_path} no fue encontrado.")
            return

        config = get_env_vars(env_path)
        
        db_config = {
            'user': config.get('DB_USERNAME'),
            'password': config.get('DB_PASSWORD'),
            'host': config.get('DB_HOST'),
            'database': config.get('DB_DATABASE'),
            'port': config.get('DB_PORT', 3306)
        }

        # --- 2. Conectar a la base de datos ---
        print(f"Conectando a la base de datos '{db_config['database']}' en '{db_config['host']}'...")
        conn = mysql.connector.connect(**db_config)
        cursor = conn.cursor()
        print("Conexión exitosa.")

        # --- 3. Preparar los datos del evento ---
        now = datetime.utcnow()
        event_data = {
            "title": "Nuevas Muestras en el Museo Quinquela Martín: Pérez Esquivel, Ferrari y Pisani",
            "category": "Artes Visuales",
            "singleDate": "2026-06-06 14:00:00",
            "locationName": "Museo Benito Quinquela Martín",
            "locationAddress": "Av. Pedro de Mendoza 1835, La Boca, Buenos Aires",
            "cost": "General: $10.000 | Residentes: $2.000 | Miércoles GRATIS.",
            "mainImageUrl": "https://upload.wikimedia.org/wikipedia/commons/e/e0/Museo_Quinquela_Mart%C3%ADn.JPG",
            "description": """El icónico museo de La Boca inaugura tres exhibiciones simultáneas que proponen un viaje profundo a la identidad argentina a través de la mirada de tres artistas fundamentales: el Premio Nobel de la Paz Adolfo Pérez Esquivel, el maestro de maestros José "Pipo" Ferrari y el fotógrafo y pintor Sergio Pisani.

### Las Propuestas:
- **Adolfo Pérez Esquivel: “María del arrabal”**: Potente relectura visual y feminista de la mítica ópera-tango “María de Buenos Aires” de Piazzolla y Ferrer.
- **Homenaje a José “Pipo” Ferrari**: Un merecido tributo a un maestro de artistas que eligió los márgenes del circuito comercial. La muestra reúne obras marcadas por la estética del noroeste argentino.
- **Sergio Pisani: “En la bajante”**: La Boca sin filtros. Lejos de la postal turística, Pisani recorre 25 años de su carrera mostrando las tensiones, los protagonistas y la belleza oculta en los rincones menos vistos del barrio.""",
            "is_featured": 1,
            "is_published": 1,
            "user_id": 1, # Asumiendo un user_id por defecto
            "created_at": now,
            "updated_at": now
        }

        # --- 4. Ejecutar la inserción ---
        print("Insertando evento en la base de datos...")
        sql = """
            INSERT INTO eventos (
                title, category, singleDate, locationName, locationAddress, cost,
                mainImageUrl, description, is_featured, is_published, user_id,
                created_at, updated_at
            ) VALUES (
                %(title)s, %(category)s, %(singleDate)s, %(locationName)s, %(locationAddress)s, %(cost)s,
                %(mainImageUrl)s, %(description)s, %(is_featured)s, %(is_published)s, %(user_id)s,
                %(created_at)s, %(updated_at)s
            )
        """
        cursor.execute(sql, event_data)
        event_id = cursor.lastrowid
        conn.commit()

        print(f"¡Éxito! Evento insertado correctamente con ID: {event_id}")

    except mysql.connector.Error as err:
        print(f"Error de MySQL: {err}")
    except Exception as e:
        print(f"Ocurrió un error inesperado: {e}")
    finally:
        if 'conn' in locals() and conn.is_connected():
            cursor.close()
            conn.close()
            print("Conexión cerrada.")

if __name__ == "__main__":
    insert_event()
