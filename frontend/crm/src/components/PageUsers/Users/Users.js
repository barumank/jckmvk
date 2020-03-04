import React, {useEffect, useCallback} from 'react'
import style from './Users.module.css'
import {Segment} from "semantic-ui-react";
import {connect} from "react-redux";
import {setPath} from "../../components/Breadcrumb/reducer";
import {findUsers, searchUsers, setPage, setSearch} from "./reducer";
import TabMenu from "../TabMenu/TabMenu";
import SearchComponent from "../../components/SearchComponent/SearchComponent";
import {useHistory, useLocation} from "react-router-dom";
import EditUser from "./EditUser/EditUser";
import {debounce} from "lodash";
import {getLoading, getPage, getSearch, getSearchLoading} from "./selectors";
import UsersTable from "./UsersTable/UsersTable";

const Users = (props) => {

    const {
        onSetPath,
        onSearchUser,
        onFindUsers,
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
            {link: '/users', label: 'Пользователи', active: true}
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
            history.push('/users');
        }
    }, [querySearch, queryPage]);

    useEffect(() => {
        onFindUsers();
    }, [queryPage]);

    const onSearchUserByKeyPress = useCallback(debounce(onSearchUser, 500), []);

    return (
        <>
            <SearchComponent loading={searchLoading} header='Пользователи' value={search}
                             onSearchChange={(event, data) => {
                                 onSetSearch(data.value);
                                 history.push(`?search=${data.value}`);
                                 onSearchUserByKeyPress();
                             }}/>
            <Segment className={style.userContainer} loading={loading}>
                <TabMenu/>
                <UsersTable/>
            </Segment>
            <EditUser/>
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
        onFindUsers() {
            dispatch(findUsers());
        },
        onSearchUser() {
            dispatch(searchUsers());
        }
    })
)(Users);
