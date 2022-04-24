const express = require("express");
const app = express.Router();
const path = require('path')

const constructorMethod = (app) => {

    app.get('/', (req, res) => {
      res.sendFile(path.resolve('static/index.html'))
    });

    app.get('/rules', (req, res) =>{
      res.sendFile(path.resolve('static/rules.html'))  
    })

    app.get('*', (req, res) => {
      res.redirect('/')
      //res.status(404).json({error: 'Not found!'});
    });
  };

module.exports = constructorMethod;