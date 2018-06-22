const $ = jQuery;

const trimAttributes = function(node) {
  $.each(node.attributes, function() {
    const attrName  = this.name;
    const attrValue = this.value;
    // remove attribute name start with "on", possible unsafe,
    // for example: onload, onerror...
    //
    // remove attribute value start with "javascript:" pseudo protocol, possible unsafe,
    // for example href="javascript:alert(1)"
    if (attrName.indexOf('on') == 0 || attrValue.indexOf('javascript:') == 0) {
        $(node).removeAttr(attrName);
    }
  });
}

export const sanitize = function(html) {
  const output = $($.parseHTML('<div>' + html + '</div>', null, false));

  output.find('*').each(function() {
    trimAttributes(this);
  });

  return output.html();
}