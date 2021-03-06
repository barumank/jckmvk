import {createSelectorCreator, defaultMemoize, createSelector} from "reselect";
import {isEqualWith} from "lodash";
import {checkChangesCreator} from "../../../library";
import customProducts from "./reducer";

export const getCategoryLoading = (state) => state.pageProducts.customProducts.categoryLoading;
export const getParentCategories = (state) => state.pageProducts.customProducts.parentCategories;

const categoriesSelectorCreator = createSelectorCreator(defaultMemoize, (object, other) => {
    return isEqualWith(object, other, checkChangesCreator((objValue, othValue) => objValue.id === othValue.id));
});
export const getCategories = categoriesSelectorCreator(
    (state) => state.pageProducts.customProducts.categories,
    (categories)=>categories
);
export const getSelectCategoryId = (state) => state.pageProducts.customProducts.selectCategoryId;
export const getParentCategoryId = (state) => state.pageProducts.customProducts.parentCategoryId;
export const getProductsLoading = (state) => state.pageProducts.customProducts.productsLoading;

const productSelectorCreator = createSelectorCreator(defaultMemoize, (object, other) => {
    return isEqualWith(object, other, checkChangesCreator((objValue, othValue) => objValue.id === othValue.id));
});
export const getProducts = productSelectorCreator(
    (state) => state.pageProducts.customProducts.products,
    (products) => products
);
const productAttributesSelectorCreator = createSelectorCreator(defaultMemoize, (object, other) => {
    return isEqualWith(object, other, checkChangesCreator((objValue, othValue) => objValue.id === othValue.id));
});
export const getProductAttributes = productAttributesSelectorCreator(
    (state) => state.pageProducts.customProducts.productAttributes,
    (productAttributes) => productAttributes
);

const attributeNamesSelectorCreator = createSelectorCreator(defaultMemoize, (object, other) => {
    return isEqualWith(object, other, checkChangesCreator((objValue, othValue) => objValue.id === othValue.id));
});
export const getAttributeNames = attributeNamesSelectorCreator(
    (state) => state.pageProducts.customProducts.attributeNames,
    (attributeNames) => attributeNames
);
export const getProductTableHeader = createSelector(
    getAttributeNames,
    (attributeNames) => {
        let out = [{key: 'id', value: '№'}, {key: 'name', value: 'Наименование'}];
        const unitAttributeName = (attributeName) => attributeName.unit === null ? '' : `(${attributeName.unit})`;
        for (let item of attributeNames) {
            out.push({key: item.id, value: `${item.name} ${unitAttributeName(item)}`});
        }
        return out;
    }
);
export const getProductTableBody = createSelector([
        getProductAttributes,
        getAttributeNames,
        getProducts
    ], (productAttributes, attributeNames, products) => {
        let productAttributeMap = new Map();
        for (let item of productAttributes) {
            if (!productAttributeMap.has(item.product_id)) {
                productAttributeMap.set(item.product_id, new Map());
            }
            let productMap = productAttributeMap.get(item.product_id);
            productMap.set(item.attribute_id, item)
        }

        return products.map((product) => {
            let row = {
                id: product.id,
                list: [
                    {key: 'id', value: product.id},
                    {key: 'name', value: product.name},
                ]
            };

            for (let attributeName of attributeNames) {
                let isAdd = false;
                if (!productAttributeMap.has(product.id)) {
                    row.list.push({key: attributeName.id, value: ''});
                    isAdd = true;
                    continue;
                }
                let pMap = productAttributeMap.get(product.id);
                for (let propId of attributeName.idList) {
                    if (pMap.has(propId)) {
                        let prop = pMap.get(propId);
                        row.list.push({key: attributeName.id, value: prop.value});
                        isAdd = true;
                        break;
                    }
                }
                if (!isAdd) {
                    row.list.push({key: attributeName.id, value: ''});
                }
            }
            return row;
        });
    }
);

const paginationSelectorCreator = createSelectorCreator(defaultMemoize, (object, other) => {
    return isEqualWith(object, other, checkChangesCreator((objValue, othValue) => objValue.page === othValue.page));
});
export const getPagination = paginationSelectorCreator(
    (state) => state.pageProducts.customProducts.pagination,
    (pagination) => pagination
);
export const getProductsPage = (state) => state.pageProducts.customProducts.productsPage;
export const getSearchProduct = (state) => state.pageProducts.customProducts.searchProduct;
export const getSearchLoading = (state) => state.pageProducts.customProducts.searchLoading;
