import User from "./components/User";
import Category from './components/Category'
import Product from './components/Product'
import Auth from "./components/Auth";
import Organization from "./components/Organization";
import axios from 'axios'

class Api {
    constructor(){
        this.user = new User(axios);
        this.category = new Category(axios);
        this.product = new Product(axios);
        this.auth = new Auth(axios);
        this.organization = new Organization(axios);
    }
}

const appApi = new Api();
export default appApi;


