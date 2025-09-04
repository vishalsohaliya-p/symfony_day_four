#!/bin/sh
set -e

# Detect Windows host IP from WSL2
HOST_IP=$(grep nameserver /etc/resolv.conf | awk '{print $2}')

echo "[INFO] Detected Windows host IP: $HOST_IP"

# Path to pg_hba.conf inside container
HBA_FILE="/var/lib/postgresql/data/pg_hba.conf"

# If file exists, clean old entries and add the new one
if [ -f "$HBA_FILE" ]; then
  sed -i '/host *all *all .* md5/d' "$HBA_FILE"
  echo "host all all 192.168.110.95/32 md5" >> "$HBA_FILE"
  echo "[INFO] Updated pg_hba.conf with host $HOST_IP"
else
  echo "[WARN] $HBA_FILE not found yet (first init?)"
fi

# Start postgres with listen_addresses=*
exec postgres -c listen_addresses='*'
