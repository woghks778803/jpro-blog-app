!function(){"use strict";var t=window.wp.data,e=window.lodash,s=window.wp.blocks;(()=>{const{editor_type:o}=eb_style_handler,n=(t,e)=>t&&e?t+"//"+e:null,c=()=>{};let i=()=>{},l={};"edit-site"===o?l=(0,t.select)("core/edit-site"):"edit-post"===o&&(l=(0,t.select)("core/edit-post")),i=l.__experimentalGetPreviewDeviceType,window.ebEditCurrentPreviewOption=i();const{isSavingPost:r=c,isAutosavingPost:a=c,isSavingNonPostEntityChanges:b=c}=(0,t.select)("core/editor"),d=/^essential\-blocks\//g,p=()=>{const{getEditedEntityRecord:i=c}=(0,t.select)("core"),{getBlocks:l=c}=(0,t.select)("core/block-editor"),{getCurrentPostId:r=c}=(0,t.select)("core/editor"),a=r(),b=l(),d={},p=t=>{for(const o of t){const{attributes:{blockMeta:t,blockRoot:c,blockId:l},innerBlocks:r}=o;if((0,e.isFunction)(s.isReusableBlock)&&(0,s.isReusableBlock)(o)){const t=i("postType","wp_block",o.attributes.ref)||{},n=t.content?(0,s.parse)((0,e.isFunction)(t.content)?t.content(t):t.content):[];for(const t of n){const{attributes:{blockMeta:e,blockRoot:s,blockId:o},innerBlocks:n}=t;e&&"essential_block"===s&&(d[o]=e),n.length>0&&p(n)}}else if((0,e.isFunction)(s.isTemplatePart)&&(0,s.isTemplatePart)(o)){const{theme:t,slug:e}=o.attributes,s=i("postType","wp_template_part",n(t,e))||{},{blocks:c=[],innerBlocks:l=[]}=s;p(c),p(l)}else t&&"essential_block"===c&&(d[l]=t);r.length>0&&p(r)}};p(b);const u=JSON.stringify(d);jQuery.ajax({type:"POST",url:ajaxurl,data:{data:u,id:a,editorType:o,action:"eb_write_block_css",nonce:eb_style_handler.sth_nonce},error:function(t){console.log(t)}})},u=()=>{if(window.ebEditCurrentPreviewOption!==i()){const e=i();window.ebEditCurrentPreviewOption=e,(t=>{let{domObj:e,resOption:s}=t;const o=e.querySelectorAll(".eb-guten-block-main-parent-wrapper > style");if(o.length<1)return!1;for(const t of o){const e=t.textContent.replace(/\s+/g," "),o=/(mimmikcssStart\s\*\/)(.+)(\/\*\smimmikcssEnd)/i;let n=" ";if("Tablet"===s){const t=(e.match(/tabcssStart\s\*\/(.+)(?=\/\*\stabcssEnd)/i)||[," "])[1];n=e.replace(o,`$1 ${t} $3`)}else if("Mobile"===s){const t=(e.match(/tabcssStart\s\*\/(.+)(?=\/\*\stabcssEnd)/i)||[," "])[1],s=(e.match(/mobcssStart\s\*\/(.+)(?=\/\*\smobcssEnd)/i)||[," "])[1];n=e.replace(o,`$1 ${t} ${s} $3`)}else n=e.replace(o,"$1  $3");t.textContent=n}})({domObj:document,resOption:e});const{isFunction:s}=lodash,{parse:o=c,isReusableBlock:l=c,isTemplatePart:r=c}=wp.blocks,{getEditedEntityRecord:a=c}=(0,t.select)("core"),{getBlocks:b=c,updateBlockAttributes:p=c}=(0,t.select)("core/block-editor"),u=b(),k=t=>{for(const c of t){const{name:t="",clientId:i,innerBlocks:b=[]}=c;if(s(l)&&l(c)){const t=a("postType","wp_block",c.attributes.ref)||{},e=t.content?o(s(t.content)?t.content(t):t.content):[];k(e)}else if(s(r)&&r(c)){const{theme:t,slug:e}=c.attributes,s=a("postType","wp_template_part",n(t,e))||{},{blocks:o=[],innerBlocks:i=[]}=s;k(o),k(i)}else d.test(t)&&p(i,{resOption:e});b.length>0&&k(b)}};k(u)}};"edit-site"===o?(0,t.subscribe)((()=>{b()&&p(),u()})):"edit-post"===o&&(0,t.subscribe)((()=>{r()&&!a()&&p(),u()}))})()}();