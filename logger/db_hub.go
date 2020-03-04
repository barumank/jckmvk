package main

import (
	"database/sql"
	"fmt"
	_ "github.com/go-sql-driver/mysql"
	"log"
	"strings"
	"sync"
	"time"
)

type DbHub struct {
	Progress  chan bool
	Close     chan bool
	WaitGroup *sync.WaitGroup
	InLog     chan LoggerMessage
	Settings  Settings
	db        *sql.DB
}

func (hub *DbHub) Run() {
	defer hub.WaitGroup.Done()

	var err error
	dsn := fmt.Sprintf("%s:%s@tcp(%s:%d)/%s",
		hub.Settings.MySql.User,
		hub.Settings.MySql.Password,
		hub.Settings.MySql.Host,
		hub.Settings.MySql.Port,
		hub.Settings.MySql.Database,
	)
	hub.db, err = sql.Open("mysql", dsn)
	if err != nil {
		close(hub.Close)
		log.Printf("error mysql connection %v", err)
		return
	}
	defer hub.db.Close()

	insertSize := hub.Settings.MySql.InsertSize
	packageSlice := make([]LoggerMessage, 0, insertSize)
	insertTimeOut := hub.Settings.MySql.InsertTimeOut
	ticker := time.NewTicker(time.Duration(insertTimeOut) * time.Second)
	for {
		select {
		case <-hub.Progress:
			return
		case logMessage := <-hub.InLog:
			packageSlice = append(packageSlice, logMessage)
			if len(packageSlice) >= insertSize {
				hub.handle(&packageSlice)
				packageSlice = packageSlice[:0]
			}
		case <-ticker.C:
			if len(packageSlice) > 0 {
				hub.handle(&packageSlice)
				packageSlice = packageSlice[:0]
			}
		}
	}
}

func (hub *DbHub) handle(packageSlice *[]LoggerMessage) bool {

	sqlQuery := "INSERT INTO " + hub.Settings.MySql.TableName + "(type,name,message,time,label) VALUES \n"
	size := len(*packageSlice)
	list := make([]string, 0, size)
	vals := make([]interface{}, 0, size)
	for _, message := range *packageSlice {
		list = append(list, "(?,?,?,?,?)")
		vals = append(vals, message.Type, message.Name, message.Message, message.Time, message.Label)
	}
	sqlQuery += strings.Join(list, ",\n")

	stmt, err := hub.db.Prepare(sqlQuery)
	if err != nil {
		return false
	}

	_, err = stmt.Exec(vals...)
	if err != nil {
		return false
	}
	_ = stmt.Close()
	return true
}
