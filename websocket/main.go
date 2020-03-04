package main

import (
	"encoding/json"
	"io/ioutil"
	"log"
	"net/http"
	"websocket/library"
	"websocket/socket"
)

// serveWs handles websocket requests from the peer.
func serveWs(hub *socket.Hub, w http.ResponseWriter, r *http.Request) {
	sessionId := ""
	cookie, err := r.Cookie("sid")
	if err == nil {
		sessionId = cookie.Value
	}
	socket.Upgrader.CheckOrigin = func(r *http.Request) bool { return true }
	conn, err := socket.Upgrader.Upgrade(w, r, nil)
	if err != nil {
		log.Println(err)
		return
	}
	client := &socket.Client{
		Hub:       hub,
		Conn:      conn,
		Send:      make(chan socket.WSMessage),
		SessionId: sessionId,
		SubscribeMessageTypeMap: map[string]bool{},
	}
	client.Hub.Register <- client
	// Allow collection of memory referenced by the caller by doing all work in
	// new goroutines.
	go client.WritePump()
	go client.ReadPump()
}

func main() {

	response, err := ioutil.ReadFile("settings.json")
	if err != nil {
		return
	}
	settings := library.Settings{}
	err = json.Unmarshal(response, &settings)
	if err != nil {
		return
	}

	rabbitService := socket.RabbitService{
		Dsn: settings.RabbitDsn,
		Workers: struct {
			Exchange     string
			ExchangeType string
			RoutingKey   string
		}{
			Exchange:     settings.Workers.Exchange,
			ExchangeType: settings.Workers.ExchangeType,
			RoutingKey:   settings.Workers.RoutingKey,
		},
		Ws: struct {
			Exchange     string
			ExchangeType string
			RoutingKey   string
			QueueName    string
		}{
			Exchange:     settings.Ws.Exchange,
			ExchangeType: settings.Ws.ExchangeType,
			RoutingKey:   settings.Ws.RoutingKey,
			QueueName:    settings.Ws.QueueName,
		},
	}

	hub := socket.NewHub()
	hub.Settings = settings
	hub.RabbitService = &rabbitService
	rabbitService.Hub = hub

	err = rabbitService.Connect()
	if err != nil {
		return
	}

	go hub.Run()

	http.HandleFunc("/ws", func(w http.ResponseWriter, r *http.Request) {
		serveWs(hub, w, r)
	})

	_ = http.ListenAndServe(":"+settings.Port, nil)
}
