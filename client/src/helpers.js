/**
 * La función para mostrar información
 *
 * La idea es ir desarrollando la función con el tiempo
 *
 * @param {string} msg El mensaje a mostrar
 */
export const logger = function (msg) {
  if (global.DEV_MSGS){
    console.log(msg)
  }
}

/**
 * Maneja los errores en los thenables de google
 *
 * @param {obj} err el error que devuelve google
 */
export const thenableRejection = (err) => {
  console.log('error en thenable')
  console.log(err)
}
