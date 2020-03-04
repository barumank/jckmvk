package main

type LoggerMessage struct {
	Type int `json:"type"`
	Name string `json:"name"`
	Message string `json:"message"`
	Time string `json:"time"`
	Label string `json:"label"`
}
