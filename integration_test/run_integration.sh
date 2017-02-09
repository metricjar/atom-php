#!/usr/bin/env bash -x
echo "Event count: ${EVENT_COUNT}"
cd integration_test
php integration_test_atom_sdk.php "${STREAM}" "${AUTH}" ${EVENT_COUNT} ${BULK_SIZE} ${BULK_SIZE_BYTE} "${DATA_TYPES}" "${DATA_INCREMENT_KEY}" ${FLUSH_INTERVAL} "${READ_FILE}" "${FILE_PATH}"