import TomSelect from "tom-select";

for(let $select of document.querySelectorAll('[data-tomselect=true]')) {
    new TomSelect($select, {
        plugins: ['dropdown_input']
    });
}
