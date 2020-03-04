package socket

const (
	TYPE_GET_USER_AUTH        = "get_user_auth"
	TYPE_SET_USER_AUTH        = "set_user_auth"
	TYPE_USER_MESSAGE         = "user_message"
	INNER_TYPE_USER_SUBSCRYBE = "subscribe"
)

type MessageInterface interface {
	Execute(hub *Hub, message *WSMessage)
}
