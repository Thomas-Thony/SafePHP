set -e
SCRIPTDIR="$(cd "$(dirname "$0")" && pwd)"

ROOTDIR="$(realpath $SCRIPTDIR/..)"

echo "Start creating essentials folders..."

if mkdir -p $ROOTDIR/SafePHP-Logs; then
    echo "Logs folder created in : $ROOTDIR"
else
    echo "Error while creating logs folder"
fi

if mkdir -p $ROOTDIR/Checksums; then
    echo "Checksums folder created in : $ROOTDIR"
else
    echo "Error while creating Chechsums folder"
fi

if mkdir -p $ROOTDIR/src/Captcha; then
    echo "Logs folder created in : $ROOTDIR"
else
    echo "Error while creating captcha keys folder"
fi

echo "Creation of basics folder made with success !"