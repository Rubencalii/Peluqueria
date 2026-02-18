#!/bin/bash

# Colores para output
GREEN='\033[0;32m'
BLUE='\033[0;34m'
RED='\033[0;31m'
NC='\033[0m' # No Color

echo -e "${BLUE}🚀 Iniciando proyecto LuxeSalon...${NC}"

# 1. Comprobar Docker
if ! docker info > /dev/null 2>&1; then
    echo -e "${RED}Error: Docker no está en ejecución.${NC}"
    exit 1
fi

# Función de limpieza al salir
cleanup() {
    echo -e "\n${BLUE}🛑 Deteniendo servicios...${NC}"
    symfony server:stop
    docker compose stop
    echo -e "${GREEN}✅ Proyecto detenido correctamente.${NC}"
    exit
}

trap cleanup SIGINT

# 2. Iniciar Base de Datos
echo -e "${BLUE}📦 Levantando contenedores Docker...${NC}"
docker compose up -d
if [ $? -ne 0 ]; then
    echo -e "${RED}Error al iniciar Docker Compose.${NC}"
    exit 1
fi

# 3. Instalar Dependencias (si es necesario)
if [ ! -d "vendor" ]; then
    echo -e "${BLUE}📚 Instalando dependencias de Composer...${NC}"
    composer install -n
fi

# 4. Esperar a la Base de Datos (simple sleep para asegurar conexión inicial)
echo -e "${BLUE}⏳ Esperando a que la base de datos esté lista...${NC}"
sleep 5

# 5. Ejecutar Migraciones
echo -e "${BLUE}🔄 Ejecutando migraciones de base de datos...${NC}"
php bin/console doctrine:migrations:migrate -n --allow-no-migration

# 6. Iniciar Servidor Symfony
echo -e "${BLUE}🌐 Iniciando servidor Symfony...${NC}"
symfony server:stop  # Detener si ya estaba corriendo
symfony server:start -d

# 7. Mensaje de éxito
echo -e "${GREEN}✨ ¡Proyecto iniciado!${NC}"
echo -e "🔗 Web: ${GREEN}http://127.0.0.1:8000${NC}"
echo -e "💻 Presiona ${RED}Ctrl+C${NC} para detener el servidor y los watchers."

# 8. Iniciar Watcher de Tailwind (bloquea la terminal y mantiene el script vivo)
echo -e "${BLUE}👀 Iniciando Tailwind Watcher...${NC}"
php bin/console tailwind:build --watch
