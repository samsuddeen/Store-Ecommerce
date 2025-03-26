import { createApp } from "vue";
import App from "./App.vue";
import Brand from "./components/Brand.vue";
import Category from "./components/Category.vue";
import CategoryEdit from "./components/CategoryEdit.vue";
import Country from "./components/Country.vue";
import Inputelement from "./components/Inputelement.vue";
import Selectelement from "./components/Selectelement.vue";
import Features from "./components/Features.vue";
import Dropzone from "./components/Dropzone.vue";
import Sizes from "./components/Sizes.vue";
import Attribute from "./components/Attribute.vue";
import Productform from "./components/ProductForm.vue";
import Oldcategory from "./components/Oldcategory.vue";
import categorysearch from "./components/category/CategorySearch.vue";
import ProductSingleImage from "./components/products/ProductImage.vue";
import { createStore } from 'vuex';


const store = createStore();


window.BASE_URL = "/api";
window.CATEGORY_ID = "";
window.FORM_SHOW = false;
const app = createApp({
    components: {
        App,
        Category,
        CategoryEdit,
        Inputelement,
        Brand,
        Country,
        Selectelement,
        Dropzone,
        Features,
        Sizes,
        Attribute,
        Productform,
        Oldcategory,
        categorysearch,
        ProductSingleImage,
    },
});
app.use(store).use({ store })
app.mount("#app");
