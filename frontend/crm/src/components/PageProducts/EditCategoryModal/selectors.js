import {createSelectorCreator, defaultMemoize} from "reselect";
import {isEqualWith} from "lodash";
import {checkChangesCreator} from "../../../library";

const getPath = (state) => state.pageProducts.editCategoryModal;
export const getIsShow = (state) => getPath(state).isShow;
export const getParentCategoryId = (state) => getPath(state).parentCategoryId;
export const getCategoryId = (state) => getPath(state).categoryId;
export const getCategoryData = (state) => getPath(state).categoryData;
export const getIsLoading = (state) => getPath(state).isLoading;
export const getType = (state) => getPath(state).type;
export const getCategoryImage = (state) => getPath(state).categoryImage;
export const getUserId = (state) => getPath(state).userId;
export const getUpdateFunction = (state) => getPath(state).updateFunction;



const categoriesSelectorCreator = createSelectorCreator(defaultMemoize, (object, other) => {
    return isEqualWith(object, other, checkChangesCreator((objValue, othValue) => objValue.id === othValue.id));
});
export const getCategories = categoriesSelectorCreator(
    (state) => getPath(state).categories,
    (categories) => {
        if (!Array.isArray(categories)) {
            return [];
        }
        return categories.map((item) => ({
            id: item.id,
            parentId: (!item.parent_id) ? 0 : item.parent_id,
            name: item.name
        }));
    }
);