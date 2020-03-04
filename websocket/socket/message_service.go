package socket

type MessageHandler struct {
	Hub *Hub
}

func (messageHandler *MessageHandler)handleBackendResponse(wsMessage *WSMessage)  {

	var message MessageInterface = nil

	switch wsMessage.Type {
	case TYPE_SET_USER_AUTH:
		message = SetUserAuthMessage{}
	case TYPE_USER_MESSAGE:
		message = UserMessage{}
	}


	if message !=nil {
		message.Execute(messageHandler.Hub,wsMessage)
	}
}

func (messageHandler *MessageHandler)handleSocketRequest(wsMessage *WSMessage)  {

	messageHandler.Hub.BackendRequest <-*wsMessage
}