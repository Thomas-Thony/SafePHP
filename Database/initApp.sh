echo "Exécution du script initBDD.sh..."

# Charger la configuration locale (valeurs par défaut) et permettre la surcharge par variables d'environnement
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
source "$SCRIPT_DIR/config.sh"
echo "Configuration chargée"
echo "WEBAPP_DB_NAME = $WEBAPP_DB_NAME"
echo "WEBAPP_USER = $WEBAPP_USER"
echo "WEBAPP_PASSWORD = $WEBAPP_PASSWORD"
echo "MYSQL_ADMIN_USER = $MYSQL_ADMIN_USER"
echo "MYSQL_ADMIN_PASSWORD = $MYSQL_ADMIN_PASSWORD"

SQL_FILE_ENV="database/sources-sql/init-BDD.sql" # chemin vers le fichier SQL contenant les commandes d'initialisation de la base de données (création de la base, utilisateurs, etc.)
SQL_FILE_BDD="database/sources-sql/${WEBAPP_DB_NAME}.sql" # chemin vers le fichier SQL contenant les structures et données de la base de données métier.

# Vérifier si les paramètres nécessaires sont présents
if [ -z "$MYSQL_ADMIN_USER" ] || [ -z "$MYSQL_ADMIN_PASSWORD" ] || [ -z "$WEBAPP_DB_NAME" ] || [ -z "$WEBAPP_USER" ] || [ -z "$WEBAPP_PASSWORD" ]; then
    echo "Les paramètres MYSQL_ADMIN_USER, MYSQL_ADMIN_PASSWORD, WEBAPP_DB_NAME, WEBAPP_USER et WEBAPP_PASSWORD doivent être renseignés (via variables d'env ou config.sh)."
    exit 1
fi

# Vérifier si les fichiers SQL existent
if [ ! -f "$SQL_FILE_ENV" ] || [ ! -f "$SQL_FILE_BDD" ]; then
    echo "Le fichier $SQL_FILE_ENV ou $SQL_FILE_BDD n'existe pas."
    exit 1
fi

# Créer un fichier temporaire avec les placeholders remplacés
TEMP_SQL_FILE_ENV="/tmp/init-BDD-processed.sql"
sed -e "s/__DATABASE_NAME__/$WEBAPP_DB_NAME/g" \
    -e "s/__WEBAPP_USER__/$WEBAPP_USER/g" \
    -e "s/__WEBAPP_PASSWORD__/$WEBAPP_PASSWORD/g" \
    "$SQL_FILE_ENV" > "$TEMP_SQL_FILE_ENV"

# Créer la base de données à partir du fichier SQL traité
echo "Création de la base de données '$WEBAPP_DB_NAME' à partir de $SQL_FILE_ENV..."
mysql -u "$MYSQL_ADMIN_USER" -p"$MYSQL_ADMIN_PASSWORD" < "$TEMP_SQL_FILE_ENV"

# Peupler la base de données à partir du fichier SQL
echo "Peuplement de la BDD à partir de $SQL_FILE_BDD..."
mysql -u "$MYSQL_ADMIN_USER" -p"$MYSQL_ADMIN_PASSWORD" -e "USE $WEBAPP_DB_NAME;" && \
mysql -u "$MYSQL_ADMIN_USER" -p"$MYSQL_ADMIN_PASSWORD" "$WEBAPP_DB_NAME" < "$SQL_FILE_BDD"

# Nettoyer le fichier temporaire
rm "$TEMP_SQL_FILE_ENV"