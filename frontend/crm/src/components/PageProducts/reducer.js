import {combineReducers} from "redux";

import baseProducts from './BaseProducts/reducer';
import customProducts from './CustomProducts/reducer';
import editCategoryModal from './EditCategoryModal/reducer'
import deleteCategoryModal from './DeleteCategoryModal/reducer'

export default combineReducers({
    baseProducts,
    customProducts,
    editCategoryModal,
    deleteCategoryModal
})

