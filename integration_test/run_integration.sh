#!/usr/bin/env bash -x
echo "Event count: ${EVENT_COUNT}"
ruby ./test/integration_test_atom_sdk.php "${EVENT_COUNT}" 