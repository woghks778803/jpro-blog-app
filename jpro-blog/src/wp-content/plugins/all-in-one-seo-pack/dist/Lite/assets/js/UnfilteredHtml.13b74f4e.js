import{u as e}from"./links.d8ef3c22.js";import{C as n}from"./index.888aa896.js";import{r as s,o as c,b as a,f as i}from"./vue.runtime.esm-bundler.0bc3eabf.js";import{_ as l}from"./_plugin-vue_export-helper.8823f7c1.js";const u={setup(){return{rootStore:e()}},components:{CoreAlert:n},data(){return{strings:{unfilteredHtmlError:this.$t.sprintf(this.$t.__("Your user account role does not have access to edit this field. %1$s",this.$td),this.$links.getDocLink(this.$constants.GLOBAL_STRINGS.learnMore,"unfilteredHtml",!0))}}}};function p(_,f,m,t,r,d){const o=s("core-alert");return t.rootStore.aioseo.user.unfilteredHtml?i("",!0):(c(),a(o,{key:0,class:"no-access",type:"red",innerHTML:r.strings.unfilteredHtmlError},null,8,["innerHTML"]))}const H=l(u,[["render",p]]);export{H as C};
