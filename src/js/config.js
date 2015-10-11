/**
 * App config
 *
 * @author Hein Bekker <hein@netbek.co.za>
 * @copyright (c) 2015 Hein Bekker
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GPLv2
 */

(function (window, chironConfig, rhea, Drupal, undefined) {

  var intv = setInterval(function () {
    if (chironConfig.isDependenciesLoaded()) {
      clearInterval(intv);

      rhea.iconConfig.set({
        'colors': {
          'black': '#222',
          'light-sky': '#2FC5DA',
          'navy': '#0A2D50',
          'white': '#FFF'
        },
        'prefix': 'icon',
        'pngUrl': Drupal.settings.chiron.url + '/img/icon/',
        'size': 256,
        'svg': 'use'
      });

      chironConfig.isSet(true);
    }
  }, 20);

})(window, window.chironConfig, window.rhea, window.Drupal);
