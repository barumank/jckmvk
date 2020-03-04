import React, {useCallback, useEffect} from 'react'
import style from './Bids.module.css';
import {Segment} from "semantic-ui-react";
import {connect} from "react-redux";
import {setPath} from "../../components/Breadcrumb/reducer";
import TabMenu from "../TabMenu/TabMenu";
import SearchComponent from "../../components/SearchComponent/SearchComponent";
import {findBids, searchBids, setSearch,setPage} from "./reducer";
import {useHistory, useLocation} from "react-router-dom";
import EditBid from "./EditBid/EditBid";
import BidsTable from "./BidsTable/BidsTable";
import {getLoading, getPage, getSearch, getSearchLoading} from "./selectors";
import {debounce} from "lodash";

const Bids = (props) => {

    const {
        onSetPath,
        onSearchBids,
        onFindBids,
        onSetSearch,
        onSetPage,
        search,
        page,
        searchLoading,
        loading
    } = props;

    const history = useHistory();

    useEffect(() => {
        onSetPath([
            {link: '/', label: 'Главная', active: false},
            {link: '/users', label: 'Пользователи', active: false},
            {link: '/users/bid', label: 'Заявки', active: true}
        ]);
    }, []);

    let query = new URLSearchParams(useLocation().search),
        querySearch = query.get('search'),
        queryPage = query.get('page');

    useEffect(() => {
        if (querySearch !== null
            && search !== querySearch) {
            onSetSearch(querySearch)
        }
        if (queryPage !== null
            && page !== queryPage) {
            onSetPage(queryPage)
        }
        if(querySearch ===''){
            history.push('/users/bid');
        }
    }, [querySearch, queryPage]);

    useEffect(() => {
        onFindBids();
    }, [queryPage]);

    const onSearchBidByKeyPress = useCallback(debounce(onSearchBids, 500), []);
    return (
        <>
            <SearchComponent loading={searchLoading} header='Заявки' value={search}
                             onSearchChange={(event, data) => {
                                 onSetSearch(data.value);
                                 history.push(`?search=${data.value}`);
                                 onSearchBidByKeyPress();
                             }}/>
            <Segment className={style.bidContainer} loading={loading}>
                <TabMenu/>
                <BidsTable/>
            </Segment>
            <EditBid/>
        </>
    );
};

export default connect(
    state => ({
        page: getPage(state),
        search: getSearch(state),
        loading: getLoading(state),
        searchLoading: getSearchLoading(state),
    }),
    dispatch => ({
        onSetPath(list) {
            dispatch(setPath(list));
        },
        onSetPage(page) {
            dispatch(setPage(page))
        },
        onSetSearch(search) {
            dispatch(setSearch(search))
        },
        onFindBids() {
            dispatch(findBids());
        },
        onSearchBids() {
            dispatch(searchBids());
        }
    })
)(Bids);
