import {combineReducers} from "redux";
import bidReducer from './Bids/reducer'
import userReducer from './Users/reducer'

export default combineReducers({
    bidList:bidReducer,
    userList:userReducer
});
