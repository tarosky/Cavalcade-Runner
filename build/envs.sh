#!/bin/bash

USER_ID="$(id -u)"
GROUP_ID="$(id -g)"
export USER_ID
export GROUP_ID
export PHP_VERSION="${PHP_VERSION:-8.0}"
export WP_VERSION="${WP_VERSION:-6.2.5}"
export WP_MULTISITE="${WP_MULTISITE:-0}"
export CAVALCADE_VERSION="${CAVALCADE_VERSION:-18}"
