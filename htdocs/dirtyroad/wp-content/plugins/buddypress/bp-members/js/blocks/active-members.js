parcelRequire=function(e,r,t,n){var i,o="function"==typeof parcelRequire&&parcelRequire,u="function"==typeof require&&require;function f(t,n){if(!r[t]){if(!e[t]){var i="function"==typeof parcelRequire&&parcelRequire;if(!n&&i)return i(t,!0);if(o)return o(t,!0);if(u&&"string"==typeof t)return u(t);var c=new Error("Cannot find module '"+t+"'");throw c.code="MODULE_NOT_FOUND",c}p.resolve=function(r){return e[t][1][r]||r},p.cache={};var l=r[t]=new f.Module(t);e[t][0].call(l.exports,p,l,l.exports,this)}return r[t].exports;function p(e){return f(p.resolve(e))}}f.isParcelRequire=!0,f.Module=function(e){this.id=e,this.bundle=f,this.exports={}},f.modules=e,f.cache=r,f.parent=o,f.register=function(r,t){e[r]=[function(e,r){r.exports=t},{}]};for(var c=0;c<t.length;c++)try{f(t[c])}catch(e){i||(i=e)}if(t.length){var l=f(t[t.length-1]);"object"==typeof exports&&"undefined"!=typeof module?module.exports=l:"function"==typeof define&&define.amd?define(function(){return l}):n&&(this[n]=l)}if(parcelRequire=f,i)throw i;return f}({"TOWc":[function(require,module,exports) {
"use strict";Object.defineProperty(exports,"__esModule",{value:!0}),exports.default=void 0;var e=wp,t=e.blockEditor.InspectorControls,n=e.components,r=n.Disabled,l=n.PanelBody,o=n.RangeControl,s=n.TextControl,a=e.element,i=a.Fragment,u=a.createElement,b=e.i18n.__,d=bp,m=d.blockComponents.ServerSideRender,p=function(e){var n=e.attributes,a=e.setAttributes,d=n.title,p=n.maxMembers;return u(i,null,u(t,null,u(l,{title:b("Settings","buddypress"),initialOpen:!0},u(s,{label:b("Title","buddypress"),value:d,onChange:function(e){a({title:e})}}),u(o,{label:b("Max members to show","buddypress"),value:p,onChange:function(e){return a({maxMembers:e})},min:1,max:15,required:!0}))),u(r,null,u(m,{block:"bp/active-members",attributes:n})))},c=p;exports.default=c;
},{}],"y7A5":[function(require,module,exports) {
"use strict";Object.defineProperty(exports,"__esModule",{value:!0}),exports.default=void 0;var e=wp,t=e.blocks.createBlock,r={from:[{type:"block",blocks:["core/legacy-widget"],isMatch:function(e){var t=e.idBase,r=e.instance;return!(null==r||!r.raw)&&"bp_core_recently_active_widget"===t},transform:function(e){var r=e.instance;return t("bp/active-members",{title:r.raw.title,maxMembers:r.raw.max_members})}}]},a=r;exports.default=a;
},{}],"dkrW":[function(require,module,exports) {
"use strict";var e=t(require("./active-members/edit")),r=t(require("./active-members/transforms"));function t(e){return e&&e.__esModule?e:{default:e}}var s=wp,i=s.blocks.registerBlockType,d=s.i18n.__;i("bp/active-members",{title:d("Recently Active Members","buddypress"),description:d("Profile photos of recently active members.","buddypress"),icon:{background:"#fff",foreground:"#d84800",src:"groups"},category:"buddypress",attributes:{title:{type:"string",default:d("Recently Active Members","buddypress")},maxMembers:{type:"number",default:15}},edit:e.default,transforms:r.default});
},{"./active-members/edit":"TOWc","./active-members/transforms":"y7A5"}]},{},["dkrW"], null)
//# sourceMappingURL=/bp-members/js/blocks/active-members.js.map