import { connect } from 'react-redux';

import SearchInput from './SearchInput';

export default function createContainer(selectors, actions) {

    const mapStateToProps = state => ({
        isLoading: selectors.isLoadingSelector(state),
        results: selectors.getResultsSelector(state),
        value: selectors.getValueSelector(state),
    });

    const mapDispatchToProps = dispatch => ({
        onSearchChange: (event, data) => {
            dispatch(actions.setIsLoading(true));
            dispatch(actions.setValue(data.value));

            appApi.product.searchProduct(data.value, appApi.category.TYPE_BASE)
                .then((response) => {
                    let products = response.products.map(item => {
                        return {
                            title: item.name,
                        }
                    });
                    dispatch(actions.setResults(products));
                    dispatch(actions.setIsLoading(false));
                });
        },
        onResultSelect: (event, {result}) => {
            dispatch(actions.setValue(result.title));
            if (actions.SEARCH_INPUT_LOADING === 'INSTANCE_1_SEARCH_INPUT_LOADING') {
                console.log(actions.SEARCH_INPUT_LOADING);
            }
        }
    });

    return connect(
        mapStateToProps,
        mapDispatchToProps
    )(SearchInput);
};