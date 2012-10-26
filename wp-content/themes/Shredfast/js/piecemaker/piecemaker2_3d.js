var flashvars = {};
flashvars.cssSource = "js/piecemaker/piecemaker.css";
flashvars.xmlSource = "js/piecemaker/piecemaker.xml";

var params = {};
params.play = "true";
params.menu = "false";
params.scale = "showall";
params.wmode = "transparent";
params.allowfullscreen = "true";
params.allowscriptaccess = "always";
params.allownetworking = "all";

swfobject.embedSWF('js/piecemaker/piecemaker.swf', 'piecemaker', '990', '466', '10', null, flashvars, params, null);