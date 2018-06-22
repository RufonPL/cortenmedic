const $ = jQuery;

export const localized = (data) => {
  return cortenscripts[data];
}

export const isRwdSize = (size) => {
  return $(`#s${size}`).css('opacity') == 1;
}

export const getMaxHeight = (element) => {
  const heights = $(element).map(function() {
    return $(this).outerHeight();
  }).get();

  return Math.max(...heights);
}

export const ShowdatePicker = (selector, options) => {
  const locales = require('flatpickr/dist/l10n/index.js'),
    language = localized('currentlang');

  const defaults = {
    dateFormat: 'd.m.Y',
    locale: locales[language]
  };

  const config = $.extend(defaults, options);

  locales.pl.firstDayOfWeek = 1;
  
  let calendar = $(selector).flatpickr(config);
}
