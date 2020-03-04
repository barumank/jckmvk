package main

type Settings struct {
	Socket struct {
		Port int    `json:"port"`
		Type string `json:"type"`
	} `json:"socket"`
	MySql struct {
		Host          string `json:"host"`
		Port          int    `json:"port"`
		User          string `json:"user"`
		Password      string `json:"password"`
		Database      string `json:"database"`
		TableName     string `json:"tableName"`
		InsertSize    int    `json:"insertSize"`
		InsertTimeOut int    `json:"insertTimeOut"`
	} `json:"mySql"`
}
