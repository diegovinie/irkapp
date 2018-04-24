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
