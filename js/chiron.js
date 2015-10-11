/**
 * Chiron
 *
 * @author Hein Bekker <hein@netbek.co.za>
 * @copyright (c) 2015 Hein Bekker
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GPLv2
 */

(function (window, undefined) {

  var namespace = 'Chiron';

  /**
   *
   * @param {String} str
   */
  function log(str) {
    if (window.chironConfig.get().core.debug) {
      console.log(str);
    }
  }

  /**
   *
   * @returns {ChironConfig}
   */
  function ChironConfig() {
    this.flags = {
      set: false
    };

    this.config = {
      core: {
        debug: false // {Boolean}
      }
    };
  }

  /**
   *
   * @returns {Boolean}
   */
  ChironConfig.prototype.isDependenciesLoaded = function () {
    return !!(window.Drupal && window.Drupal.settings && window.Drupal.settings.chiron);
  };

  /**
   *
   * @param {Boolean} value
   * @returns {Boolean}
   */
  ChironConfig.prototype.isSet = function (value) {
    if (arguments.length > 0) {
      this.flags.set = value;
    }
    else {
      return this.flags.set;
    }
  };

  /**
   *
   * @param {Object} values
   */
  ChironConfig.prototype.set = function (values) {
    lodash.merge(this.config, values);
  };

  /**
   *
   * @returns {Object}
   */
  ChironConfig.prototype.get = function () {
    return this.config;
  };

  /**
   *
   * @returns {Chiron}
   */
  function Chiron() {
    this.flags = {
      init: false // {Boolean}
    };
  }

  /**
   *
   * @returns {Boolean}
   */
  Chiron.prototype.isDependenciesLoaded = function () {
    return !!(window.chironConfig.isSet() && window.Drupal && window.FastClick && window.jQuery && window.rhea && window.rhea.icon);
  };

  /**
   *
   * @param {Promise}
   */
  Chiron.prototype.init = function () {
    log(namespace + '.init()');

    if (this.flags.init) {
      return this.initDeferred.promise;
    }

    this.initDeferred = Q.defer();
    this.flags.init = true;

    // Render icons.
    rhea.icon.apply();

    // Init FastClick.
    FastClick.attach(document.body);

    this.initDeferred.resolve(true);

    return this.initDeferred.promise;
  };

  window.chiron = new Chiron();
  window.chironConfig = new ChironConfig();

})(window);

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

/**
 * Initialization
 *
 * @author Hein Bekker <hein@netbek.co.za>
 * @copyright (c) 2015 Hein Bekker
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GPLv2
 */

(function (window, undefined) {

  var intv = setInterval(function () {
    if (window.chiron && window.chiron.isDependenciesLoaded()) {
      clearInterval(intv);

      window.chiron.init();
    }
  }, 20);

})(window);
