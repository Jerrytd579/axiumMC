const express = require('express');
const router = express.Router();

router.get('/', async (req, res) => {
    try{
        console.log("lmao xd andrew gay")
    } catch(e){
        res.status(500).send();
    }
})

module.exports = router;