import{c as f,b as d}from"./vue-router.964734d4.js";import{u as y,k as _,l as w}from"./links.d8ef3c22.js";import{a as h}from"./allowed.a855ba11.js";const c=(t,n,r)=>{const e=n[r];return e?()=>{const o=c(t,n,r+1);e({...t,next:o})}:t.next},R=(t,n)=>{const r=f({history:d(`wp-admin/admin.php?page=aioseo-${window.aioseo.page}`),routes:t,scrollBehavior(e,o,a){return a||(e.hash?{selector:e.hash}:{left:0,top:0})}});return r.beforeEach((e,o,a)=>{const s=y(),l=_();s.loaded||w(),s.ping();const m=e.meta.access;if(!h(m))return e.meta.home!==o.name?r.replace({name:e.meta.home}):null;if(e.meta.middleware){const i=Array.isArray(e.meta.middleware)?e.meta.middleware:[e.meta.middleware],u={app:n,from:o,next:a,router:r,to:e},p=c(u,i,1);return i[0]({...u,next:p})}return l.resetPageNumbers(),a()}),r},A=(t,n)=>{const r=t[n];return r?typeof r=="function"?r():Promise.resolve(r):new Promise((e,o)=>{(typeof queueMicrotask=="function"?queueMicrotask:setTimeout)(o.bind(null,new Error("Unknown variable dynamic import: "+n)))})};export{A as _,R as s};
