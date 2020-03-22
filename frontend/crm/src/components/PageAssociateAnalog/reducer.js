import {combineReducers} from "redux";
import searchProduct from './componets/SearchProduct/reducer';
import searchProductAnalog from './componets/SearchProductAnalog/reducer';

export default combineReducers({
    searchProduct: searchProduct,
    searchProductAnalog: searchProductAnalog
});
