import {createSelectorCreator, defaultMemoize, createSelector} from "reselect";
import {isEqualWith} from "lodash";
import {checkChangesCreator} from "../../library";

const getPath = (state) => state.pageProductDetail;
export const getProductData = (state) => getPath(state).productData;
export const getIsLoading = (state) => getPath(state).isLoading;
export const getProductsLoading = (state) => getPath(state).productsLoading;
export const getProductsBrandLoading = (state) => getPath(state).productsBrandLoading;
export const getProductBrandData = (state) => getPath(state).productBrandData;

const productSelectorCreator = createSelectorCreator(defaultMemoize, (object, other) => {
    return isEqualWith(object, other, checkChangesCreator((objValue, othValue) => objValue.id === othValue.id));
});
export const getProducts = productSelectorCreator(
    (state) => state.pageProductDetail.products,
    (products) => products
);
const productAttributesSelectorCreator = createSelectorCreator(defaultMemoize, (object, other) => {
    return isEqualWith(object, other, checkChangesCreator((objValue, othValue) => objValue.id === othValue.id));
});
export const getProductAttributes = productAttributesSelectorCreator(
    (state) => state.pageProductDetail.productAttributes,
    (productAttributes) => productAttributes
);

const attributeNamesSelectorCreator = createSelectorCreator(defaultMemoize, (object, other) => {
    return isEqualWith(object, other, checkChangesCreator((objValue, othValue) => objValue.id === othValue.id));
});
export const getAttributeNames = attributeNamesSelectorCreator(
    (state) => state.pageProductDetail.attributeNames,
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





const productBrandSelectorCreator = createSelectorCreator(defaultMemoize, (object, other) => {
    return isEqualWith(object, other, checkChangesCreator((objValue, othValue) => objValue.id === othValue.id));
});
export const getProductsBrand = productBrandSelectorCreator(
    (state) => state.pageProductDetail.productsBrand,
    (products) => products
);
const productAttributesBrandSelectorCreator = createSelectorCreator(defaultMemoize, (object, other) => {
    return isEqualWith(object, other, checkChangesCreator((objValue, othValue) => objValue.id === othValue.id));
});
export const getProductAttributesBrand = productAttributesBrandSelectorCreator(
    (state) => state.pageProductDetail.productAttributesBrand,
    (productAttributes) => productAttributes
);

const attributeNamesBrandSelectorCreator = createSelectorCreator(defaultMemoize, (object, other) => {
    return isEqualWith(object, other, checkChangesCreator((objValue, othValue) => objValue.id === othValue.id));
});
export const getAttributeNamesBrand = attributeNamesBrandSelectorCreator(
    (state) => state.pageProductDetail.attributeNamesBrand,
    (attributeNames) => attributeNames
);
export const getProductTableHeaderBrand = createSelector(
    getAttributeNamesBrand,
    (attributeNames) => {
        let out = [{key: 'id', value: '№'}, {key: 'name', value: 'Наименование'}];
        const unitAttributeName = (attributeName) => attributeName.unit === null ? '' : `(${attributeName.unit})`;
        for (let item of attributeNames) {
            out.push({key: item.id, value: `${item.name} ${unitAttributeName(item)}`});
        }
        return out;
    }
);
export const getProductTableBodyBrand = createSelector([
        getProductAttributesBrand,
        getAttributeNamesBrand,
        getProductsBrand
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