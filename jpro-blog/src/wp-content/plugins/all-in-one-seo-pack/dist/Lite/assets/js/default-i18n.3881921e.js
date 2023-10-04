function G(t,e){var r=0,n,i;e=e||{};function c(){var o=n,l=arguments.length,d,u;t:for(;o;){if(o.args.length!==arguments.length){o=o.next;continue}for(u=0;u<l;u++)if(o.args[u]!==arguments[u]){o=o.next;continue t}return o!==n&&(o===i&&(i=o.prev),o.prev.next=o.next,o.next&&(o.next.prev=o.prev),o.next=n,o.prev=null,n.prev=o,n=o),o.val}for(d=new Array(l),u=0;u<l;u++)d[u]=arguments[u];return o={args:d,val:t.apply(null,d)},n?(n.prev=o,o.next=n):i=o,r===e.maxSize?(i=i.prev,i.next=null):r++,n=o,o.val}return c.clear=function(){n=null,i=null,r=0},c}var ot=typeof globalThis<"u"?globalThis:typeof window<"u"?window:typeof global<"u"?global:typeof self<"u"?self:{};function J(t){return t&&t.__esModule&&Object.prototype.hasOwnProperty.call(t,"default")?t.default:t}function at(t){if(t.__esModule)return t;var e=t.default;if(typeof e=="function"){var r=function n(){return this instanceof n?Reflect.construct(e,arguments,this.constructor):e.apply(this,arguments)};r.prototype=e.prototype}else r={};return Object.defineProperty(r,"__esModule",{value:!0}),Object.keys(t).forEach(function(n){var i=Object.getOwnPropertyDescriptor(t,n);Object.defineProperty(r,n,i.get?i:{enumerable:!0,get:function(){return t[n]}})}),r}var K={};(function(t){(function(){var e={not_string:/[^s]/,not_bool:/[^t]/,not_type:/[^T]/,not_primitive:/[^v]/,number:/[diefg]/,numeric_arg:/[bcdiefguxX]/,json:/[j]/,not_json:/[^j]/,text:/^[^\x25]+/,modulo:/^\x25{2}/,placeholder:/^\x25(?:([1-9]\d*)\$|\(([^)]+)\))?(\+)?(0|'[^$])?(-)?(\d+)?(?:\.(\d+))?([b-gijostTuvxX])/,key:/^([a-z_][a-z_\d]*)/i,key_access:/^\.([a-z_][a-z_\d]*)/i,index_access:/^\[(\d+)\]/,sign:/^[+-]/};function r(l){return i(o(l),arguments)}function n(l,d){return r.apply(null,[l].concat(d||[]))}function i(l,d){var u=1,_=l.length,s,w="",y,x,f,A,m,E,S,a;for(y=0;y<_;y++)if(typeof l[y]=="string")w+=l[y];else if(typeof l[y]=="object"){if(f=l[y],f.keys)for(s=d[u],x=0;x<f.keys.length;x++){if(s==null)throw new Error(r('[sprintf] Cannot access property "%s" of undefined value "%s"',f.keys[x],f.keys[x-1]));s=s[f.keys[x]]}else f.param_no?s=d[f.param_no]:s=d[u++];if(e.not_type.test(f.type)&&e.not_primitive.test(f.type)&&s instanceof Function&&(s=s()),e.numeric_arg.test(f.type)&&typeof s!="number"&&isNaN(s))throw new TypeError(r("[sprintf] expecting number but found %T",s));switch(e.number.test(f.type)&&(S=s>=0),f.type){case"b":s=parseInt(s,10).toString(2);break;case"c":s=String.fromCharCode(parseInt(s,10));break;case"d":case"i":s=parseInt(s,10);break;case"j":s=JSON.stringify(s,null,f.width?parseInt(f.width):0);break;case"e":s=f.precision?parseFloat(s).toExponential(f.precision):parseFloat(s).toExponential();break;case"f":s=f.precision?parseFloat(s).toFixed(f.precision):parseFloat(s);break;case"g":s=f.precision?String(Number(s.toPrecision(f.precision))):parseFloat(s);break;case"o":s=(parseInt(s,10)>>>0).toString(8);break;case"s":s=String(s),s=f.precision?s.substring(0,f.precision):s;break;case"t":s=String(!!s),s=f.precision?s.substring(0,f.precision):s;break;case"T":s=Object.prototype.toString.call(s).slice(8,-1).toLowerCase(),s=f.precision?s.substring(0,f.precision):s;break;case"u":s=parseInt(s,10)>>>0;break;case"v":s=s.valueOf(),s=f.precision?s.substring(0,f.precision):s;break;case"x":s=(parseInt(s,10)>>>0).toString(16);break;case"X":s=(parseInt(s,10)>>>0).toString(16).toUpperCase();break}e.json.test(f.type)?w+=s:(e.number.test(f.type)&&(!S||f.sign)?(a=S?"+":"-",s=s.toString().replace(e.sign,"")):a="",m=f.pad_char?f.pad_char==="0"?"0":f.pad_char.charAt(1):" ",E=f.width-(a+s).length,A=f.width&&E>0?m.repeat(E):"",w+=f.align?a+s+A:m==="0"?a+A+s:A+a+s)}return w}var c=Object.create(null);function o(l){if(c[l])return c[l];for(var d=l,u,_=[],s=0;d;){if((u=e.text.exec(d))!==null)_.push(u[0]);else if((u=e.modulo.exec(d))!==null)_.push("%");else if((u=e.placeholder.exec(d))!==null){if(u[2]){s|=1;var w=[],y=u[2],x=[];if((x=e.key.exec(y))!==null)for(w.push(x[1]);(y=y.substring(x[0].length))!=="";)if((x=e.key_access.exec(y))!==null)w.push(x[1]);else if((x=e.index_access.exec(y))!==null)w.push(x[1]);else throw new SyntaxError("[sprintf] failed to parse named argument key");else throw new SyntaxError("[sprintf] failed to parse named argument key");u[2]=w}else s|=2;if(s===3)throw new Error("[sprintf] mixing positional and named placeholders is not (yet) supported");_.push({placeholder:u[0],param_no:u[1],keys:u[2],sign:u[3],pad_char:u[4],align:u[5],width:u[6],precision:u[7],type:u[8]})}else throw new SyntaxError("[sprintf] unexpected placeholder");d=d.substring(u[0].length)}return c[l]=_}t.sprintf=r,t.vsprintf=n,typeof window<"u"&&(window.sprintf=r,window.vsprintf=n)})()})(K);const q=J(K),B=G(console.error);function ut(t,...e){try{return q.sprintf(t,...e)}catch(r){return r instanceof Error&&B(`sprintf error: 

`+r.toString()),t}}var k,U,T,X;k={"(":9,"!":8,"*":7,"/":7,"%":7,"+":6,"-":6,"<":5,"<=":5,">":5,">=":5,"==":4,"!=":4,"&&":3,"||":2,"?":1,"?:":1};U=["(","?"];T={")":["("],":":["?","?:"]};X=/<=|>=|==|!=|&&|\|\||\?:|\(|!|\*|\/|%|\+|-|<|>|\?|\)|:/;function Q(t){for(var e=[],r=[],n,i,c,o;n=t.match(X);){for(i=n[0],c=t.substr(0,n.index).trim(),c&&e.push(c);o=r.pop();){if(T[i]){if(T[i][0]===o){i=T[i][1]||i;break}}else if(U.indexOf(o)>=0||k[o]<k[i]){r.push(o);break}e.push(o)}T[i]||r.push(i),t=t.substr(n.index+i.length)}return t=t.trim(),t&&e.push(t),e.concat(r.reverse())}var V={"!":function(t){return!t},"*":function(t,e){return t*e},"/":function(t,e){return t/e},"%":function(t,e){return t%e},"+":function(t,e){return t+e},"-":function(t,e){return t-e},"<":function(t,e){return t<e},"<=":function(t,e){return t<=e},">":function(t,e){return t>e},">=":function(t,e){return t>=e},"==":function(t,e){return t===e},"!=":function(t,e){return t!==e},"&&":function(t,e){return t&&e},"||":function(t,e){return t||e},"?:":function(t,e,r){if(t)throw e;return r}};function W(t,e){var r=[],n,i,c,o,l,d;for(n=0;n<t.length;n++){if(l=t[n],o=V[l],o){for(i=o.length,c=Array(i);i--;)c[i]=r.pop();try{d=o.apply(null,c)}catch(u){return u}}else e.hasOwnProperty(l)?d=e[l]:d=+l;r.push(d)}return r[0]}function Y(t){var e=Q(t);return function(r){return W(e,r)}}function N(t){var e=Y(t);return function(r){return+e({n:r})}}var D={contextDelimiter:"",onMissingKey:null};function tt(t){var e,r,n;for(e=t.split(";"),r=0;r<e.length;r++)if(n=e[r].trim(),n.indexOf("plural=")===0)return n.substr(7)}function j(t,e){var r;this.data=t,this.pluralForms={},this.options={};for(r in D)this.options[r]=e!==void 0&&r in e?e[r]:D[r]}j.prototype.getPluralForm=function(t,e){var r=this.pluralForms[t],n,i,c;return r||(n=this.data[t][""],c=n["Plural-Forms"]||n["plural-forms"]||n.plural_forms,typeof c!="function"&&(i=tt(n["Plural-Forms"]||n["plural-forms"]||n.plural_forms),c=N(i)),r=this.pluralForms[t]=c),r(e)};j.prototype.dcnpgettext=function(t,e,r,n,i){var c,o,l;return i===void 0?c=0:c=this.getPluralForm(t,i),o=r,e&&(o=e+this.options.contextDelimiter+r),l=this.data[t][o],l&&l[c]?l[c]:(this.options.onMissingKey&&this.options.onMissingKey(r,t),c===0?r:n)};const H={"":{plural_forms(t){return t===1?0:1}}},et=/^i18n\.(n?gettext|has_translation)(_|$)/,nt=(t,e,r)=>{const n=new j({}),i=new Set,c=()=>{i.forEach(a=>a())},o=a=>(i.add(a),()=>i.delete(a)),l=(a="default")=>n.data[a],d=(a,p="default")=>{var h;n.data[p]={...n.data[p],...a},n.data[p][""]={...H[""],...(h=n.data[p])==null?void 0:h[""]},delete n.pluralForms[p]},u=(a,p)=>{d(a,p),c()},_=(a,p="default")=>{var h;n.data[p]={...n.data[p],...a,"":{...H[""],...(h=n.data[p])==null?void 0:h[""],...a==null?void 0:a[""]}},delete n.pluralForms[p],c()},s=(a,p)=>{n.data={},n.pluralForms={},u(a,p)},w=(a="default",p,h,b,v)=>(n.data[a]||d(void 0,a),n.dcnpgettext(a,p,h,b,v)),y=(a="default")=>a,x=(a,p)=>{let h=w(p,void 0,a);return r?(h=r.applyFilters("i18n.gettext",h,a,p),r.applyFilters("i18n.gettext_"+y(p),h,a,p)):h},f=(a,p,h)=>{let b=w(h,p,a);return r?(b=r.applyFilters("i18n.gettext_with_context",b,a,p,h),r.applyFilters("i18n.gettext_with_context_"+y(h),b,a,p,h)):b},A=(a,p,h,b)=>{let v=w(b,void 0,a,p,h);return r?(v=r.applyFilters("i18n.ngettext",v,a,p,h,b),r.applyFilters("i18n.ngettext_"+y(b),v,a,p,h,b)):v},m=(a,p,h,b,v)=>{let F=w(v,b,a,p,h);return r?(F=r.applyFilters("i18n.ngettext_with_context",F,a,p,h,b,v),r.applyFilters("i18n.ngettext_with_context_"+y(v),F,a,p,h,b,v)):F},E=()=>f("ltr","text direction")==="rtl",S=(a,p,h)=>{var F,R;const b=p?p+""+a:a;let v=!!((R=(F=n.data)==null?void 0:F[h??"default"])!=null&&R[b]);return r&&(v=r.applyFilters("i18n.has_translation",v,a,p,h),v=r.applyFilters("i18n.has_translation_"+y(h),v,a,p,h)),v};if(t&&u(t,e),r){const a=p=>{et.test(p)&&c()};r.addAction("hookAdded","core/i18n",a),r.addAction("hookRemoved","core/i18n",a)}return{getLocaleData:l,setLocaleData:u,addLocaleData:_,resetLocaleData:s,subscribe:o,__:x,_x:f,_n:A,_nx:m,isRTL:E,hasTranslation:S}};function Z(t){return typeof t!="string"||t===""?(console.error("The namespace must be a non-empty string."),!1):/^[a-zA-Z][a-zA-Z0-9_.\-\/]*$/.test(t)?!0:(console.error("The namespace can only contain numbers, letters, dashes, periods, underscores and slashes."),!1)}function L(t){return typeof t!="string"||t===""?(console.error("The hook name must be a non-empty string."),!1):/^__/.test(t)?(console.error("The hook name cannot begin with `__`."),!1):/^[a-zA-Z][a-zA-Z0-9_.-]*$/.test(t)?!0:(console.error("The hook name can only contain numbers, letters, dashes, periods and underscores."),!1)}function P(t,e){return function(n,i,c,o=10){const l=t[e];if(!L(n)||!Z(i))return;if(typeof c!="function"){console.error("The hook callback must be a function.");return}if(typeof o!="number"){console.error("If specified, the hook priority must be a number.");return}const d={callback:c,priority:o,namespace:i};if(l[n]){const u=l[n].handlers;let _;for(_=u.length;_>0&&!(o>=u[_-1].priority);_--);_===u.length?u[_]=d:u.splice(_,0,d),l.__current.forEach(s=>{s.name===n&&s.currentIndex>=_&&s.currentIndex++})}else l[n]={handlers:[d],runs:0};n!=="hookAdded"&&t.doAction("hookAdded",n,i,c,o)}}function O(t,e,r=!1){return function(i,c){const o=t[e];if(!L(i)||!r&&!Z(c))return;if(!o[i])return 0;let l=0;if(r)l=o[i].handlers.length,o[i]={runs:o[i].runs,handlers:[]};else{const d=o[i].handlers;for(let u=d.length-1;u>=0;u--)d[u].namespace===c&&(d.splice(u,1),l++,o.__current.forEach(_=>{_.name===i&&_.currentIndex>=u&&_.currentIndex--}))}return i!=="hookRemoved"&&t.doAction("hookRemoved",i,c),l}}function I(t,e){return function(n,i){const c=t[e];return typeof i<"u"?n in c&&c[n].handlers.some(o=>o.namespace===i):n in c}}function z(t,e,r=!1){return function(i,...c){const o=t[e];o[i]||(o[i]={handlers:[],runs:0}),o[i].runs++;const l=o[i].handlers;if(!l||!l.length)return r?c[0]:void 0;const d={name:i,currentIndex:0};for(o.__current.push(d);d.currentIndex<l.length;){const _=l[d.currentIndex].callback.apply(null,c);r&&(c[0]=_),d.currentIndex++}if(o.__current.pop(),r)return c[0]}}function C(t,e){return function(){var c;var n;const i=t[e];return(n=(c=i.__current[i.__current.length-1])==null?void 0:c.name)!==null&&n!==void 0?n:null}}function M(t,e){return function(n){const i=t[e];return typeof n>"u"?typeof i.__current[0]<"u":i.__current[0]?n===i.__current[0].name:!1}}function $(t,e){return function(n){const i=t[e];if(L(n))return i[n]&&i[n].runs?i[n].runs:0}}class rt{constructor(){this.actions=Object.create(null),this.actions.__current=[],this.filters=Object.create(null),this.filters.__current=[],this.addAction=P(this,"actions"),this.addFilter=P(this,"filters"),this.removeAction=O(this,"actions"),this.removeFilter=O(this,"filters"),this.hasAction=I(this,"actions"),this.hasFilter=I(this,"filters"),this.removeAllActions=O(this,"actions",!0),this.removeAllFilters=O(this,"filters",!0),this.doAction=z(this,"actions"),this.applyFilters=z(this,"filters",!0),this.currentAction=C(this,"actions"),this.currentFilter=C(this,"filters"),this.doingAction=M(this,"actions"),this.doingFilter=M(this,"filters"),this.didAction=$(this,"actions"),this.didFilter=$(this,"filters")}}function st(){return new rt}const it=st(),g=nt(void 0,void 0,it),ct=g.getLocaleData.bind(g),lt=g.setLocaleData.bind(g),ft=g.resetLocaleData.bind(g),pt=g.subscribe.bind(g),dt=g.__.bind(g),ht=g._x.bind(g),_t=g._n.bind(g),gt=g._nx.bind(g),yt=g.isRTL.bind(g),bt=g.hasTranslation.bind(g);export{dt as _,_t as a,gt as b,ht as c,nt as d,ct as e,yt as f,at as g,bt as h,g as i,lt as j,pt as k,J as l,ot as m,ft as r,ut as s};
