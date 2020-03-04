import React, {useEffect, useCallback} from 'react'
import {connect} from "react-redux";
import {setPath} from "../../components/Breadcrumb/reducer";
import {
    getCategories,
    getCategoryLoading, getPagination, getParentCategories,
    getParentCategoryId, getProductsLoading, getProductsPage,
    getSelectCategoryId, getSearchProduct, getProductTableHeader, getProductTableBody, getSearchLoading
} from "./selectors";
import SearchComponent from "../../components/SearchComponent/SearchComponent";
import ProductsTemplate from "../components/ProductsTemplate/ProductsTemplate";
import TopControl from "../components/TopControl/TopControl";
import CategoryContainer from "../components/CategoryContainer/CategoryContainer";
import ProductAndCategoryContainer from "../components/ProductAndCategoryContainer/ProductAndCategoryContainer";
import ProductContainer from "../components/ProductContainer/ProductContainer";
import {useHistory, useLocation} from "react-router-dom";
import {
    selectParentCategory,
    selectCategory,
    findCategories,
    selectProductsPage,
    findProducts,
    searchProductsAndCategories, setSearchProducts, searchProducts
} from "./reducer";
import ProductList from "../../components/ProductList/ProductList";
import {debounce} from "lodash";
import CategoryList from "../../components/CategoryList/CategoryList";
import {editCategory, newCategory} from "../EditCategoryModal/reducer";
import {showDeleteCategory} from "../DeleteCategoryModal/reducer";


const getCategoryUrl = (categoryId, parentId, page = "1") => {
    return `/products?categoryId=${categoryId}&parentId=${parentId}&page=${page}`;
};
const getSearchUrl = (categoryId, search, page = "1") => {
    return `/products?categoryId=${categoryId}&search=${search}&page=${page}`;
};

const CustomProducts  = (props) => {
    const {
        onSetPath,
        searchProduct,
        searchLoading,
        onSetSearchProduct,
        onSearchProducts,
        onSearchProductsAndCategories,
        categoryLoading,
        parentCategories,
        categories,
        selectCategoryId,
        parentCategoryId,
        productsLoading,
        productsPage,
        pagination,
        productTableHeader,
        productTableBody,
        onSelectCategory,
        onSelectParentCategory,
        onFindCategories,
        onSelectProductsPage,
        onFindProducts,
        onShowNewCategoryModal,
        onEditCategory,
        onDeleteCategory
    } = props;
    let history = useHistory();

    let query = new URLSearchParams(useLocation().search),
        queryCategoryId = query.get('categoryId'),
        queryParentId = query.get('parentId'),
        queryPage = query.get('page'),
        querySearch = query.get('search');
    queryCategoryId = queryCategoryId === null ? "0" : queryCategoryId;
    queryParentId = queryParentId === null ? "0" : queryParentId;
    queryPage = queryPage === null ? "1" : queryPage;
    querySearch = querySearch === null ? "" : querySearch;


    useEffect(() => {
        if (queryParentId !== parentCategoryId) {
            onSelectParentCategory(queryParentId);
        }
        if (queryCategoryId !== selectCategoryId) {
            onSelectCategory(queryCategoryId);
        }
        if (querySearch !== searchProduct) {
            onSetSearchProduct(querySearch);
        }
        if (queryPage !== productsPage) {
            onSelectProductsPage(queryPage);
        }
    }, [queryCategoryId, queryParentId, queryPage, querySearch]);

    //хлебные крошки
    useEffect(() => {
        let breadcrumb = [
            {link: '/', label: 'Главная', active: false},
        ];
        if (parentCategories.length === 0 && querySearch === '') {
            breadcrumb.push( {link: '/products', label: 'Товары', active: false},);
            onSetPath(breadcrumb);
            return;
        }
        if (querySearch !== '') {
            breadcrumb.push( {link: '/products', label: 'Товары', active: false},);
            breadcrumb.push({link: '', label: 'Поиск', active: true});
            onSetPath(breadcrumb);
            return;
        }

        breadcrumb.push({link: '/products', label: 'Товары', active: false},);
        for (let i = 0; i < parentCategories.length; i++) {
            if (i === parentCategories.length - 1) {
                breadcrumb.push({
                    link: getCategoryUrl(0, parentCategories[i].id),
                    label: parentCategories[i].name,
                    active: true
                });
                break;
            }
            breadcrumb.push({
                link: getCategoryUrl(0, parentCategories[i].id),
                label: parentCategories[i].name,
                active: false
            });
        }
        onSetPath(breadcrumb);
    }, [parentCategories]);

    //загрузка категорий
    useEffect(() => {
        if (querySearch === '') {
            onFindCategories();
        }
    }, [queryParentId, querySearch]);

    //загрузка товаров
    useEffect(() => {
        if (querySearch === '') {
            onFindProducts();
        } else {
            onSearchProducts();
        }
    }, [queryCategoryId, queryParentId, queryPage]);

    const selectCategory = (id) => {
        if (searchProduct === '') {
            history.push(getCategoryUrl(id, parentCategoryId));
        } else {
            history.push(getSearchUrl(id, searchProduct));
        }
    };
    const openCategory = (id) => {
        history.push(getCategoryUrl(0, id));
    };
    const openProductPage = (page) => {
        if (searchProduct === '') {
            return getCategoryUrl(selectCategoryId, parentCategoryId, page);
        }
        return getSearchUrl(selectCategoryId, searchProduct, page);
    };

    let onBack = null;
    if (parentCategories.length > 0) {
        onBack = () => {
            if (parentCategories[parentCategories.length - 2] !== undefined) {
                history.push(getCategoryUrl(0, parentCategories[parentCategories.length - 2].id));
            } else {
                history.push(getCategoryUrl(0, 0));
            }
        }
    }
    const productCategory = () => {
        if (selectCategoryId !== '0' && selectCategoryId !== 0) {
            for (let category of categories) {
                if (category.id === selectCategoryId) {
                    return category;
                }
            }
        } else if (parentCategoryId !== '0' && parentCategoryId !== 0) {
            for (let category of parentCategories) {
                if (category.id === parentCategoryId) {
                    return category;
                }
            }
        }
        return {id: '0', name: 'Все категрии', user_id: '0'};
    };

    let showNewCategoryModal = onShowNewCategoryModal;
    if (parentCategories.length > 0) {
        showNewCategoryModal = ()=>onShowNewCategoryModal(parentCategories[parentCategories.length-1].id);
    }

    const onSearchProductByKeyPress = useCallback(debounce(onSearchProductsAndCategories, 1000), []);

    return (
        <>
            <SearchComponent header="Товары" value={searchProduct} loading={searchLoading}
                             placeholder='Поиск товара по артиклу / наименованию / бренду...'
                             onSearchChange={(event, data) => {
                                 history.push(`?search=${data.value}`);
                                 onSearchProductByKeyPress();
                             }}/>
            <ProductsTemplate>
                <TopControl onBack={onBack} showNewCategoryModal={showNewCategoryModal} />
                <ProductAndCategoryContainer>
                    <CategoryContainer loading={categoryLoading}>
                        <CategoryList
                            categories={categories}
                            currentCategoryId={selectCategoryId}
                            onSelectCategory={selectCategory}
                            onOpenCategory={openCategory}
                            onEditCategory={onEditCategory}
                            onDeleteCategory={onDeleteCategory}
                        />
                    </CategoryContainer>
                    <ProductContainer categoryName={productCategory().name} loading={productsLoading}>
                        <ProductList
                            openProductPage={openProductPage}
                            productsPage={productsPage}
                            pagination={pagination}
                            productTableHeader={productTableHeader}
                            productTableBody={productTableBody}
                        />
                    </ProductContainer>
                </ProductAndCategoryContainer>
            </ProductsTemplate>
        </>
    );
};


export default connect(
    state => ({
        categoryLoading: getCategoryLoading(state),
        parentCategories: getParentCategories(state),
        categories: getCategories(state),
        selectCategoryId: getSelectCategoryId(state),
        parentCategoryId: getParentCategoryId(state),
        productsLoading: getProductsLoading(state),
        pagination: getPagination(state),
        productsPage: getProductsPage(state),
        searchProduct: getSearchProduct(state),
        productTableHeader: getProductTableHeader(state),
        productTableBody: getProductTableBody(state),
        searchLoading: getSearchLoading(state),
    }),
    dispatch => ({
        onSetPath(list) {
            dispatch(setPath(list));
        },
        onSelectCategory(id) {
            dispatch(selectCategory(id));
        },
        onSelectParentCategory(id) {
            dispatch(selectParentCategory(id));
        },
        onFindCategories() {
            dispatch(findCategories());
        },
        onSelectProductsPage(page) {
            dispatch(selectProductsPage(page));
        },
        onFindProducts() {
            dispatch(findProducts());
        },
        onSetSearchProduct(search) {
            dispatch(setSearchProducts(search));
        },
        onSearchProductsAndCategories(search) {
            dispatch(searchProductsAndCategories());
        },
        onSearchProducts(search) {
            dispatch(searchProducts());
        },
        onShowNewCategoryModal(categoryId = 0) {
            dispatch(newCategory(appApi.category.TYPE_CUSTOM, categoryId, ()=>dispatch(findCategories())))
        },
        onEditCategory(categoryId) {
            dispatch(editCategory(categoryId,()=>dispatch(findCategories())))
        },
        onDeleteCategory(categoryId) {
            dispatch(showDeleteCategory(categoryId,()=>dispatch(findCategories())))
        }
    })
)(CustomProducts);