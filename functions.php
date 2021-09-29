<?php
    /*******************/
    //Economy Functions//
    /*******************/
    function getTaxIncomeByProvinceID($link, $id) {
        $sqlget = 'SELECT isCity, hasBanking, baseTax, hasReligiousSite FROM provinces WHERE ID = ' . $id;
        $sqldata = mysqli_query($link, $sqlget);
        $sqlProvince = mysqli_fetch_assoc($sqldata);
        if($sqlProvince != null) {
            $taxEff = 0.75;
            if($sqlProvince['isCity'] == true) {
                $taxEff += 0.25;
            }

            if($sqlProvince['hasBanking'] == true) {
                $taxEff += 0.20;
            }

            if($sqlProvince['hasReligiousSite'] == true) {
                $taxEff += 0.60;
            }
            $tax = ($sqlProvince['baseTax']) * $taxEff * 10;
            return $tax;
        }
    }

    function getProvinceProductionIncomeByID($link, $id) {
        $sqlget = 'SELECT baseProd, tradegood FROM provinces WHERE ID = ' . $id;
        $sqldata = mysqli_query($link, $sqlget);
        $sqlProvince = mysqli_fetch_assoc($sqldata);
        if($sqlProvince != null) {
            $goodsProduced = getProvinceGoodsProducedByID($link, $id);
            $tradeValue = $goodsProduced * getTradeGoodValueByID($link, $sqlProvince['tradegood']);
            $productionincome = $tradeValue * getProductionEfficiency($link, $id);
            return $productionincome * 10;
        }
    }

    function getProductionEfficiency($link, $id){
        $efficiency = 1.55;
        $sqlget = 'SELECT culture FROM provinces WHERE ID = ' . $id;
        $sqldata = mysqli_query($link, $sqlget);
        $sqlProvince = mysqli_fetch_assoc($sqldata);
        if($sqlProvince['culture'] == 1) {
            $efficiency += 0.25;
        }
        return $efficiency;
    }
    /*
    function getProvinceIncomeByID($link, $id) {
        return getProvinceProductionIncomeByID($link, $id) + getTaxIncomeByProvinceID($link, $id);
    }
*/
    function getProvinceDevelopmentByType($link, $id, $type) {
        $sqlget = 'SELECT baseMP, baseTax, baseProd FROM provinces WHERE ID = ' . $id;
        $sqldata = mysqli_query($link, $sqlget);
        $sqlProvince = mysqli_fetch_assoc($sqldata);
        if($type == 1) {
            return $sqlProvince['baseTax'];
        } else if($type == 2) {
            return $sqlProvince['baseProd'];
        } else if($type == 3) {
            return $sqlProvince['baseMP'];
        }
        return "error";
    }

    function getProvinceGoodsProducedByID($link, $id) {
        $goodsproducedmod = 1;
        $goodsProduced = getProvinceDevelopmentByType($link, $id, 2) * 0.2;
        $terrainID = getProvinceTerrainID($link, $id);
        $tradeGood = getProvinceTradeGoodIDByID($link, $id);
        if($terrainID == 1) {
            if($tradeGood == 2 || $tradeGood == 5 || $tradeGood == 7 || $tradeGood == 9) {
                $goodsproducedmod += 0.5;
            }
        }

        if($terrainID == 2) {
            if($tradeGood == 2 || $tradeGood == 5 || $tradeGood == 7 || $tradeGood == 9) {
                $goodsproducedmod += 0.4;
            }
        }

        if($terrainID == 4) {
            if($tradeGood == 3 || $tradeGood == 8) {
                $goodsproducedmod += 0.5;
            }
        }

        if($terrainID == 5) {
            if($tradeGood == 6 || $tradeGood == 11 || $tradeGood == 13 || $tradeGood == 14) {
                $goodsproducedmod += 0.2;
            }
        }

        if($terrainID == 8) {
            if($tradeGood == 17 || $tradeGood == 18 || $tradeGood == 10) {
                $goodsproducedmod += 0.3;
            }
        }

        if($terrainID == 9) {
            if($tradeGood == 6 || $tradeGood == 11 || $tradeGood == 13 || $tradeGood == 14) {
                $goodsproducedmod += 0.4;
            }
        }
        
        if($terrainID == 15) {
            if($tradeGood == 6 || $tradeGood == 11 || $tradeGood == 13 || $tradeGood == 14) {
                $goodsproducedmod += 0.5;
            }
        }

        if($terrainID == 16) {
            if($tradeGood == 17 || $tradeGood == 18 || $tradeGood == 10) {
                $goodsproducedmod += 0.8;
            }
        }

        $sqlget = 'SELECT hasAlchemy FROM provinces WHERE ID = ' . $id;
        $sqldata = mysqli_query($link, $sqlget);
        $sqlProvince = mysqli_fetch_assoc($sqldata);

        if($sqlProvince['hasAlchemy']) {
            $goodsproducedmod += 0.2;
        }

        $goodsProduced *= $goodsproducedmod;
        return $goodsProduced;
    }

    function getNationTaxIncomeByID($link, $id) {   
        $taxIncome = 1;
        $sqlget = 'SELECT id FROM provinces WHERE house = ' . $id;
        $sqldata = mysqli_query($link, $sqlget);
        while($province = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
            $taxIncome += getTaxIncomeByProvinceID($link, $province['id']);
        }
        return $taxIncome;
    }

    function getNationProductionIncomeByID($link, $id) {
        $prodIncome = 0;
        $sqlget = 'SELECT id FROM provinces WHERE house = ' . $id;
        $sqldata = mysqli_query($link, $sqlget);
        while($province = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
            $prodIncome += getProvinceProductionIncomeByID($link, $province['id']);
        }
        return $prodIncome;
    }

    function getNationIncomeByID($link, $id) {
        return getNationProvincialIncome($link, $id) + getNationTradeNodeIncomeByID($link, $id) + getVassalIncome($link, $id) + calculateNationLoanIncomes($link, $id);
    }

    function getStateMaintenaceOfNationByID($link, $id) {
        $sqlget = 'SELECT ID FROM provinces WHERE house = ' . $id;
        $sqldata = mysqli_query($link, $sqlget);
        $maintenance = 0;
        while ($row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
            $maintenance += ((getProvinceTotalDevelopmentByID($link, $row['ID'])) * 0.01) * 12 * 15;
        }
        return $maintenance;
    }

    function getVassalIncome($link, $id) {
        $sqlget = 'SELECT ID, liegeTax FROM house WHERE liegeID = ' . $id;
        $sqldata = mysqli_query($link, $sqlget);
        $vassalincome = 0;
        while($house = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
            $vassalincome += (getNationIncomeByID($link, $house['ID']) * $house['liegeTax']) ;
        }
        return $vassalincome;
    }

    function getProvinceIncomeByID($link, $id) {
        $sqlget = 'SELECT baseProd, tradegood, isCity, hasBanking, baseTax, hasReligiousSite FROM provinces WHERE ID = ' . $id;
        $sqldata = mysqli_query($link, $sqlget);
        $sqlProvince = mysqli_fetch_assoc($sqldata);
        if($sqlProvince != null) {
            $goodsProduced = getProvinceGoodsProducedByID($link, $id);
            $tradeValue = $goodsProduced * getTradeGoodValueByID($link, $sqlProvince['tradegood']);
            $productionincome = $tradeValue * getProductionEfficiency($link, $id);
            $productionincome *= 10;
        }
        
        if($sqlProvince != null) {
            $taxEff = 0.75;
            if($sqlProvince['isCity'] == true) {
                $taxEff += 0.25;
            }

            if($sqlProvince['hasBanking'] == true) {
                $taxEff += 0.20;
            }

            if($sqlProvince['hasReligiousSite'] == true) {
                $taxEff += 0.60;
            }
            $tax = ($sqlProvince['baseTax']) * $taxEff * 10;
            return $tax;
        }

        return $productionincome + $tax;
    }

    function getNationProvincialIncome($link, $id) { 
        $income = 1;
        $sqlget = 'SELECT id FROM provinces WHERE house = ' . $id;
        $sqldata = mysqli_query($link, $sqlget);
        while($province = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
            $income += getProvinceIncomeByID($link, $province['id']);
        }
        return $income;
    }

    /*****************/
    //Trade Functions//
    /*****************/
    function getProvinceTradepowerByID($link, $id) {
        $base = getProvinceTotalDevelopmentByID($link, $id) * 0.2;
        $percentModifiers = 1;
        $sqlget = 'SELECT isCoastal, CoT, hasMarketplace FROM provinces WHERE ID = ' . $id;
        $sqldata = mysqli_query($link, $sqlget);
        $sqlProvince = mysqli_fetch_assoc($sqldata);
        if($sqlProvince['CoT'] == 3) {
            $base += 45;
        } else if($sqlProvince['CoT'] == 2) {
            $base += 15;
        } else if($sqlProvince['CoT'] == 1) {
            $base += 5;
        }
        if($sqlProvince['isCoastal']) {
            $percentModifiers += 0.25;
        }
        if($sqlProvince['hasMarketplace']) {
            $percentModifiers += 0.3;
        }
        return $base * $percentModifiers;
    }

    function getProvinceTradeGoodIDByID($link, $id) {
        $sqlget = 'SELECT tradegood FROM provinces WHERE ID = ' . $id;
        $sqldata = mysqli_query($link, $sqlget);
        $sqlProvince = mysqli_fetch_assoc($sqldata);
        return $sqlProvince['tradegood'];
    }

    function getProvinceTradeValueByID($link, $id) {
        return getProvinceGoodsProducedByID($link, $id) * getTradeGoodValueByID($link, getProvinceTradeGoodIDByID($link, $id)) * 10;
    }

    function getTradeNodePowerByID($link, $id) {
        $sqlget = 'SELECT id FROM provinces WHERE node = ' . $id;
        $sqldata = mysqli_query($link, $sqlget);
        $tradePower = 0;
        while($province = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
            $tradePower += getProvinceTradepowerByID($link, $province['id']);
        }

        $sqlgetmerchants = 'SELECT house FROM merchants WHERE node = ' . $id;
        $sqlmerchants = mysqli_query($link, $sqlgetmerchants);
        $houses = array();
        while($house = mysqli_fetch_array($sqlmerchants, MYSQLI_ASSOC)) {
            $tradePower += 2;
        }

        return $tradePower;
    }

    function getTradeNodeValueByID($link, $id) {
        $sqlget = 'SELECT id FROM provinces WHERE node = ' . $id;
        $sqldata = mysqli_query($link, $sqlget);
        $tradeValue = 0;
        while($province = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
            $tradeValue += getProvinceTradeValueByID($link, $province['id']);
        }
        return $tradeValue + getIncomingTradeValue($link, $id);
    }

    function getNationTradePowerInNodeByID($link, $id, $node) {
        $tradepowerinnode = 0;
        $sqlget = 'SELECT ID FROM provinces WHERE house = ' . $id . ' AND node = ' . $node;
        $sqldata = mysqli_query($link, $sqlget);
        while($province = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
            $tradepowerinnode += getProvinceTradepowerByID($link, $province['ID']);
        }
        $sqlgetmerchants = 'SELECT house FROM merchants WHERE house = ' . $id . ' AND node = ' . $node;
        $sqlmerchants = mysqli_fetch_array(mysqli_query($link, $sqlgetmerchants), MYSQLI_ASSOC);
        if($sqlmerchants !== NULL) {
            if($sqlmerchants['house'] == $id) {
                $tradepowerinnode += 2; 
            }
        }
        return $tradepowerinnode;
    }

    function getNationTradeNodeIncomeByID($link, $id) {
        $sqlget = 'SELECT homenode, hasJewelcrafting FROM house WHERE ID = ' . $id;
        $sqldata = mysqli_query($link, $sqlget);
        $sqlNode = mysqli_fetch_assoc($sqldata);
        $homenode = $sqlNode['homenode'];
        $tradenodevalue = getTradeNodeValueByID($link, $homenode);
        $totaltradepowerinnode = getTradeNodePowerByID($link, $homenode);
        
        $tradepowerinnode = getNationTradePowerInNodeByID($link, $id, $sqlNode['homenode']);

        $collectedvalue = $tradenodevalue * ($tradepowerinnode / $totaltradepowerinnode);
        $tradeeff = 1.3;
        if($sqlNode['hasJewelcrafting']) {
            $tradeeff += 0.2;
        }
        $earnedvalue = $collectedvalue * $tradeeff;
        return $earnedvalue;
    }

    function getIncomingTradeValue($link, $id) {
        //Get upstream nodes
        $sqlget = 'SELECT upstream FROM tradenodes WHERE ID = ' . $id;
        $sqldata = mysqli_query($link, $sqlget);
        $sqlNodes = mysqli_fetch_assoc($sqldata);

        //Calculate the incoming trade value from those nodes
        //OTV = TTV x (Transferring TP / Total TP)
        $ITV = 0;

        //make array of upstream nodes
        $nodesarray = explode(',', $sqlNodes['upstream']);

        //sum of outgoing trade value of upstream nodes
        foreach($nodesarray as $node) {
            $ITV += getOutgoingTradeValueFromNodeToNode($link, $node, $id);
            //$ITV += 4;
        }
        
        return $ITV;
    }

    function getOutgoingTradeValueFromNodeToNode($link, $node, $downstream) {
        if($node == "" || $node == NULL || !isset($node)) {
            return 0;
        }
        //get trade power of those collecting
        $sqlget = 'SELECT ID FROM house WHERE homenode = ' . $node;
        $sqldata = mysqli_query($link, $sqlget);
        $sqlHousesCollecting = mysqli_fetch_assoc($sqldata);
        $collectingTP = 0;
        while($house = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
            $collectingTP += getNationTradePowerInNodeByID($link, $house['ID'], $node);
        }

        //get total trade power in node
        $TTP = getTradeNodePowerByID($link, $node);

        //get trade power of those transferring
        //total trade power - collecting trade power = transferring power
        //if TTP is greater than zero
        if($TTP > 0) {
            $STP = $TTP - $collectingTP;
            $STPerc = $STP / $TTP;
        } else if ($TTP == 0) {
            return 0;
        }

        //Total Trade Value being transferred
        $OTV = $STPerc * $TTP;

        //get steered trade value to that node
        $merchants = getArrayOfHousesWithMerchantsSteeringInNodeToAnother($link, $node, $downstream);
        $x = 0;
        foreach($merchants as $merchant) {
            $x += getNationTradePowerInNodeByID($link,  $merchant, $node);
        }

        //STV = OTV x (steered to downstream node TP / all steering TPs)
        $STV = $OTV * ($x / $TTP);
        return $STV;
    }
    
    function hasMerchantPresent($link, $house, $node) {
        $sqlgetmerchants = 'SELECT transferringnode FROM merchants WHERE house = ' . $house . ' AND node = ' . $node;
        $sqlmerchants = mysqli_fetch_array(mysqli_query($link, $sqlgetmerchants), MYSQLI_ASSOC);
        return $sqlmerchants['transferringnode'];
    }

    function getArrayOfHousesWithMerchantsSteeringInNodeToAnother($link, $node, $downstreamnode) {
        $sqlgetmerchants = 'SELECT house FROM merchants WHERE node = ' . $node . ' AND transferringnode = ' . $downstreamnode;
        $sqlmerchants = mysqli_query($link, $sqlgetmerchants);
        $houses = array();
        while($house = mysqli_fetch_array($sqlmerchants, MYSQLI_ASSOC)) {
            array_push($houses, $house['house']);
        }
        return $houses;
    }

    /***************************/
    //Warfare Economy Functions//
    /***************************/
    function getRegimentMaintenanceByID($link, $id) {
        $sqlget = 'SELECT strength, type FROM regiments WHERE ID = ' . $id;
        $sqldata = mysqli_query($link, $sqlget);
        $sqlRegiment = mysqli_fetch_assoc($sqldata);
        $strength = $sqlRegiment['strength'];   
        $technology = 0.38;
        if($sqlRegiment['type'] == 0) { $basecost = 20; } else { $basecost = 50;}
        $regimentcost = 0.3 * ($strength / 1000) * ($basecost) * ($technology);
        return $regimentcost;
    }

    function getNationArmyMaintenanceByID($link, $id) {
        $sqlget = 'SELECT ID FROM regiments WHERE house = ' . $id;
        $sqldata = mysqli_query($link, $sqlget);
        $maintenance = 0;
        while ($row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
            $maintenance += getRegimentMaintenanceByID($link, $row['ID']);
        }
        return $maintenance;
    }

    function getShipMaintenanceByID($link, $id) {
        $sqlget = 'SELECT type FROM ships WHERE ID = ' . $id;
        $sqldata = mysqli_query($link, $sqlget);
        $sqlShip = mysqli_fetch_assoc($sqldata);
        $type = $sqlShip['type'];
        $technology = 1.8;
        
        if($sqlShip['type'] == 0) { 
            $maintenancefactor = 0.05; 
            $basecost = 1000;
        } else if($sqlShip['type'] == 1) { 
            $maintenancefactor = 0.015; 
            $basecost = 500;
        } else if($sqlShip['type'] == 2) { 
            $maintenancefactor = 0.02; 
            $basecost = 800;
        } else if($sqlShip['type'] == 3) { 
            $maintenancefactor = 0.02; 
            $basecost = 250;
        }
        
        $basenavalmaint = $basecost * $maintenancefactor;

        $shipmaintenance = $basenavalmaint * $technology;
        return $shipmaintenance;
    }

    function getNationNavalMaintenanceByID($link, $id) {
        $sqlget = 'SELECT ID FROM ships WHERE house = ' . $id;
        $sqldata = mysqli_query($link, $sqlget);
        $maintenance = 0;
        while ($row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
            $maintenance += getShipMaintenanceByID($link, $row['ID']);
        }
        return $maintenance;
    }

    function getProvinceLandForceLimitByID($link, $id) {
        $forcelimit = getProvinceTotalDevelopmentByID($link, $id) * 0.2;
        $sqlget = 'SELECT tradegood FROM provinces WHERE ID = ' . $id;
        $sqldata = mysqli_query($link, $sqlget);
        $sqlProvince = mysqli_fetch_assoc($sqldata);

        if($sqlProvince['tradegood'] == 2) {
            $forcelimit += 0.5;
        }
        return $forcelimit;
    }

    function getNationLandForceLimitByID($link, $id) {
        $landforcelimit = 3;
        $sqlget = 'SELECT ID FROM provinces WHERE house = ' . $id;
        $sqldata = mysqli_query($link, $sqlget);
        while($province = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
            $landforcelimit += getProvinceLandForceLimitByID($link, $province['ID']);
        }
        return $landforcelimit;
    }

    function getProvinceNavalForceLimitByID($link, $id) {
        $sqlget = 'SELECT isCoastal, tradegood, hasShipyard, isCity, CoT FROM provinces WHERE ID = ' . $id;
        $sqldata = mysqli_query($link, $sqlget);
        $sqlProvince = mysqli_fetch_assoc($sqldata);
        if(!$sqlProvince['isCoastal']){
            return 0;
        }

        $forcelimit = getProvinceTotalDevelopmentByID($link, $id) * 0.4;
        if($sqlProvince['tradegood'] == 8) {
            $forcelimit += 1;
        }

        if($sqlProvince['hasShipyard'] == 1) {
            $forcelimit += 8;
        }

        if($sqlProvince['CoT'] > 0) {
            $forcelimit += 8;
        }

        if($sqlProvince['isCity'] == 1) {
            $forcelimit *= 1.1;
        }

        return $forcelimit;
    }

    function getNationNavalForceLimitByID($link, $id) {
        $navalforcelimit = 6;

        $sqlget = 'SELECT ID FROM provinces WHERE house = ' . $id;
        $sqldata = mysqli_query($link, $sqlget);
        while($province = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
            $navalforcelimit += getProvinceNavalForceLimitByID($link, $province['ID']);
        }
        return $navalforcelimit;
    }    
    
    function getNationMaxManpowerByID($link, $id) {
        $base = 100;
        $sqlget = 'SELECT id FROM provinces WHERE house = ' . $id;
        $sqldata = mysqli_query($link, $sqlget);
        while($province = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
            $base += getProvinceManpowerIncreaseByID($link, $province['id']);
        }
        return $base;
    }

    function getNationMaxSailorsByID($link, $id) {
        $base = 0;
        $sqlget = 'SELECT id FROM provinces WHERE house = ' . $id;
        $sqldata = mysqli_query($link, $sqlget);
        while($province = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
            $base += getProvinceSailorsByID($link, $province['id']);
        }
        return $base;
    }

    function getProvinceManpowerIncreaseByID($link, $id) {
        $sqlget = 'SELECT baseMP, core FROM provinces WHERE ID = ' . $id;
        $sqldata = mysqli_query($link, $sqlget);
        $sqlProvince = mysqli_fetch_assoc($sqldata);
        $MPEff = 0.25;
        if($sqlProvince['core']) {
            $MPEff += 0.75;
        }
        $manpowerincrease = ($sqlProvince['baseMP'] * 500) * $MPEff;
        return $manpowerincrease;
    }

    function getProvinceSailorsByID($link, $id) {
        $sqlget = 'SELECT isCoastal, isCity, core, CoT FROM provinces WHERE ID = ' . $id;
        $sqldata = mysqli_query($link, $sqlget);
        $sqlProvince = mysqli_fetch_assoc($sqldata);
        $sailorEff = 1;
        if($sqlProvince['isCoastal']) {
            $base = getProvinceTotalDevelopmentByID($link, $id) * 80;
            if($sqlProvince['CoT'] > 0) {
                 $sailorEff += (0.25 * $sqlProvince['CoT']);
            }
            if($sqlProvince['core']) {
                $sailorEff += 0.75;
            }
            if($sqlProvince['isCity']) {
                $sailorEff += 0.25;
            }
            $base = $base * $sailorEff;
            return $base;
        } else {
            return 0;
        }
    }

    /*******************/
    //Warfare Functions//
    /*******************/
    function getSupplyLimit($link, $houseid, $provinceid) {
        $supplylimit = 6;
        $sqlget = 'SELECT house, terrain, isCoastal, baseTax, baseProd, baseMP FROM provinces WHERE ID = ' . $provinceid;
        $sqldata = mysqli_fetch_array(mysqli_query($link, $sqlget), MYSQLI_ASSOC);
        $terrainID = $sqldata['terrain'];
        $sqlget1 = 'SELECT supplylimitbase FROM terrain WHERE ID = ' . $terrainID;
        $sqldata1 = mysqli_fetch_array(mysqli_query($link, $sqlget1), MYSQLI_ASSOC);
        $supplylimit = $supplylimit + $sqldata1['supplylimitbase'];
        
        $supplylimiteff = 1;
        if($sqldata['isCoastal']) {
            $supplylimiteff = $supplylimiteff + 0.5;
        }

        if($sqldata['house'] == $houseid) {
            $supplylimiteff = $supplylimiteff + 0.25;
        }

        $development = $sqldata['baseTax'] + $sqldata['baseProd'] + $sqldata['baseMP'];
        $supplylimiteff = $supplylimiteff + ($development * 0.02);
        $supplylimit = ($supplylimit * $supplylimiteff);
        return $supplylimit;
    }

    function getArmyAttrition($link, $armyid, $houseid) {
        $sqlget = 'SELECT strength FROM regiments WHERE army = ' . $armyid;
        $sqldata = mysqli_fetch_array(mysqli_query($link, $sqlget), MYSQLI_ASSOC);
        $sqlget1 = 'SELECT location FROM armies WHERE ID = ' . $armyid;
        $sqldata1 = mysqli_fetch_array(mysqli_query($link, $sqlget1), MYSQLI_ASSOC);
        while($regiment = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
            $totalarmysize = $regiment['strength'];
        }
        $supplylimit = getSupplyLimit($link, $houseid, $sqldata1['location']);
        $attrition = (($totalarmysize / 1000) - round($supplylimit)) * (10 / round($supplylimit));
        return $attrition;
    }

    /******************/
    //Province Getters//
    /******************/
    function getProvinceTerrainID($link, $id) {
        $sqlget = 'SELECT terrain FROM provinces WHERE ID = ' . $id;
        $sqldata = mysqli_fetch_array(mysqli_query($link, $sqlget), MYSQLI_ASSOC);
        return $sqldata['terrain'];
    }

    function getTradeGoodValueByID($link, $id) {
        $sqlget = 'SELECT price FROM tradegoods WHERE ID = ' . $id;
        $sqldata = mysqli_query($link, $sqlget);
        $sqlGood = mysqli_fetch_assoc($sqldata);
        return $sqlGood['price'];
    }

    function getProvinceTotalDevelopmentByID($link, $id) {
        $sqlget = 'SELECT baseMP, baseTax, baseProd FROM provinces WHERE ID = ' . $id;
        $sqldata = mysqli_query($link, $sqlget);
        $sqlProvince = mysqli_fetch_assoc($sqldata);
        $development = $sqlProvince['baseMP'] + $sqlProvince['baseProd'] + $sqlProvince['baseTax'];
        return $development;
    }

    /***************/
    //House Getters//
    /***************/
    function getHouseMoneyByID($link, $id) {
        $sqlget = 'SELECT money FROM house WHERE ID = ' . $id;
        $sqldata = mysqli_fetch_array(mysqli_query($link, $sqlget), MYSQLI_ASSOC);
        return $sqldata['money'];
    }

    function getHouseNameByID($link, $id) {
        $sqlget = 'SELECT name FROM house WHERE ID = ' . $id;
        $sqldata = mysqli_fetch_array(mysqli_query($link, $sqlget), MYSQLI_ASSOC);
        return $sqldata['name'];
    }

    function getProvinceNameByID($link, $id) {
        $sqlget = 'SELECT name FROM provinces WHERE ID = ' . $id;
        $sqldata = mysqli_fetch_array(mysqli_query($link, $sqlget), MYSQLI_ASSOC);
        return $sqldata['name'];
    }
    
    function getNationManpower($link, $id) {
        $sqlget = 'SELECT manpower FROM house WHERE ID = ' . $id;
        $sqldata = mysqli_fetch_array(mysqli_query($link, $sqlget), MYSQLI_ASSOC);
        return $sqldata['manpower'];
    }

    function getNationSailors($link, $id) {
        $sqlget = 'SELECT sailors FROM house WHERE ID = ' . $id;
        $sqldata = mysqli_fetch_array(mysqli_query($link, $sqlget), MYSQLI_ASSOC);
        return $sqldata['sailors'];
    }

    /*********/
    //Setters//
    /*********/
    function setHouseCash($link, $id, $amount) {
        $sql = 'UPDATE house SET money = ' . $amount . ' WHERE ID = ' . $id;
        if($sqlresult = mysqli_query($link, $sql)) {
            return 1;
        } else {
            return 0;
        }
    }

    function setHouseCashByDifference($link, $id, $change) {
        $sql = $sql = 'UPDATE house SET money = money + ' . $change . ' WHERE ID = ' . $id;
        if($sqlresult = mysqli_query($link, $sql)) {
            return 1;
        } else {
            return 0;
        }
    }

    function setHouseManpower($link, $id, $amount) {
        $sql = 'UPDATE house SET manpower = ' . $amount . ' WHERE ID = ' . $id;
        if($sqlresult = mysqli_query($link, $sql)) {
            return 1;
        } else {
            return 0;
        }
    }

    function setHouseSailors($link, $id, $amount) {
        $sql = 'UPDATE house SET sailors = ' . $amount . ' WHERE ID = ' . $id;
        if($sqlresult = mysqli_query($link, $sql)) {
            return 1;
        } else {
            return 0;
        }
    }

    /****************/
    //Army Functions//
    /****************/
    //Creates a new army and returns it's ID
    function createNewArmy($link, $houseid, $province) {
        $sql = "INSERT INTO armies (house, location) VALUES (" . $houseid . ", " . $province . ")";
        $sqlinsert = mysqli_query($link, $sql);
        return mysqli_insert_id($link);
    }

    function createNewRegiments($link, $houseid, $inf, $cav, $armyid) {
        $sql = "INSERT INTO regiments (strength, house, type, army) VALUES ";
        for($i = 1; $i <= $inf; $i++) {
            if($i == $inf) {
                $sql = $sql . "('1000', " . $houseid . ", 0, " . $armyid . ");";
            } else {
                $sql = $sql . "('1000', " . $houseid . ", 0, " . $armyid . "), ";
            }
        }
        $sql1 = "INSERT INTO regiments (strength, house, type, army) VALUES ";

        for($i = 1; $i <= $cav; $i++) {
            if($i == $cav) {
                $sql1 = $sql1 . "('1000', " . $houseid . ", 1, " . $armyid . ");";
            } else {
                $sql1 = $sql1 . "('1000', " . $houseid . ", 1, " . $armyid . "), ";
            }
        }
        $totalmanpower = ($inf + $cav) * 1000;
        $manpower = getNationManpower($link, $houseid) - $totalmanpower;
        $sql2 = "UPDATE house SET manpower = " . $manpower . " WHERE ID = " . $houseid;
        $sqlinsert = mysqli_query($link, $sql);
        $sqlinsert1 = mysqli_query($link, $sql1);
        $sqlinsert2 = mysqli_query($link, $sql2);
    }

    /****************/
    //Loan Functions//
    /****************/
    //Creates a new loan and returns it's ID
    function createNewLoan($link, $amount, $interest, $creditor, $debtor, $period, $active) {
        $sql = "INSERT INTO loans (amount, interest, creditor, debter, period, active) VALUES (" . $amount . ", " . $interest . ", " . $creditor . ", " . $debtor . ", " . $period . ", " . $active . ")";
        $sqlinsert = mysqli_query($link, $sql);
        return mysqli_insert_id($link);
    }

    function sendLoanOffer($link, $creditor, $debtor, $interest, $amount, $period) {
        $housename = getHouseNameByID($link, $creditor);
        $loanid = createNewLoan($link, $amount, $interest, $creditor, $debtor, $period, false);
        $text = $housename . " has offered you a loan of " . $amount . " at " . $interest . "% interest for " . $period . " months";
        $sql = "INSERT INTO notifications (house, text, type, associateditem) VALUES (" . $debtor . ", " . $text . ", 1, " . $loanid . ")";
        $sqlinsert  = mysqli_query($link, $sql);
        return mysqli_insert_id($link);
    }

    function acceptLoanOffer($link, $loanid, $notifid) {
        //Delete notification
        $notifsql = "DELETE FROM notifications WHERE ID = " . $notifid;
        mysqli_query($link, $notifsql);

        //activate loan
        $loansql = "UPDATE loans SET active = true WHERE ID = " . $loanid;
        
        //get loan info
        $sqlget = 'SELECT amount, creditor, debter FROM loans WHERE ID = ' . $id;
        $sqldata = mysqli_fetch_array(mysqli_query($link, $sqlget), MYSQLI_ASSOC);

        $amount = $sqldata['amount'];
        //add money to debtor
        setHouseCashByDifference($link, $sqldata['debter'], $amount);
        $amount = -1 * abs($amount);
        //take money from creditor
        setHouseCashByDifference($link, $sqldata['creditor'], $amount);
    }

    function calculateNationLoanRepayments($link, $id) {
        $sqlget = 'SELECT * FROM loans WHERE debter = ' . $id;
        $sqldata = mysqli_query($link, $sqlget);
        $loanrepayments = 0;
        while ($loan = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
            $a = $loan['amount'];
            $r = ($loan['interest'] / 100) / 12;
            $n = $loan['period'];
            $p = ($r * $a) / (1 - pow(1 + $r, (-1 * abs($n))));
            $loanrepayments +=$p;
        }
        return $loanrepayments;
    }

    function calculateNationLoanIncomes($link, $id) {
        $sqlget = 'SELECT * FROM loans WHERE creditor = ' . $id;
        $sqldata = mysqli_query($link, $sqlget);
        $loanrepayments = 0;
        while ($loan = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
            $a = $loan['amount'];
            $r = ($loan['interest'] / 100) / 12;
            $n = $loan['period'];
            $p = ($r * $a) / (1 - pow(1 + $r, (-1 * abs($n))));
            $loanrepayments += $p;
        }
        return $loanrepayments;
    }

    /************************/
    //Notification Functions//
    /************************/
    function deletenotification($link, $id) {
        $notifsql = "DELETE FROM notifications WHERE ID = " . $notifid;
        return mysqli_query($link, $notifsql);
    }

    /****************/
    //Navy Functions//
    /****************/
    //Creates a new naavy and returns it's ID
    function createNewNavy($link, $houseid, $province) {
        $sql = "INSERT INTO navies (house, location) VALUES (" . $houseid . ", " . $province . ")";
        $sqlinsert = mysqli_query($link, $sql);
        return mysqli_insert_id($link);
    }

    function createNewShips($link, $houseid, $heavy, $light, $galley, $transport, $navyid) {
        $sql = "INSERT INTO ships (navy, type, house, hullstrength) VALUES ";
        for($i = 1; $i <= $heavy; $i++) {
            if($i == $heavy) {
                $sql = $sql . "(". $navyid . ", 0, " . $houseid . ", 90);";
            } else {
                $sql = $sql . "(". $navyid . ", 0, " . $houseid . ", 90), ";
            }
        }

        $sql1 = "INSERT INTO ships (navy, type, house, hullstrength) VALUES ";
        for($i = 1; $i <= $light; $i++) {
            if($i == $light) {
                $sql1 = $sql1 . "(". $navyid . ", 1, " . $houseid . ", 24);";
            } else {
                $sql1 = $sql1 . "(". $navyid . ", 1, " . $houseid . ", 24), ";
            }
        }

        $sql2 = "INSERT INTO ships (navy, type, house, hullstrength) VALUES ";
        for($i = 1; $i <= $light; $i++) {
            if($i == $galley) {
                $sql2 = $sql2 . "(". $navyid . ", 2, " . $houseid . ", 24);";
            } else {
                $sql2 = $sql2 . "(". $navyid . ", 2, " . $houseid . ", 24), ";
            }
        }

        $sql3 = "INSERT INTO ships (navy, type, house, hullstrength) VALUES ";
        for($i = 1; $i <= $light; $i++) {
            if($i == $transport) {
                $sql3 = $sql3 . "(". $navyid . ", 3, " . $houseid . ", 36);";
            } else {
                $sql3 = $sql3 . "(". $navyid . ", 3, " . $houseid . ", 36), ";
            }
        }

        $totalsailors = ($heavy * 400) + ($light * 200) + ($galley * 300) + ($transport * 100);
        $sailors = getNationSailors($link, $houseid) - $totalsailors;
        $sql4 = "UPDATE house SET sailors = " . $sailors . " WHERE ID = " . $houseid;
        $sqlinsert = mysqli_query($link, $sql);
        $sqlinsert1 = mysqli_query($link, $sql1);
        $sqlinsert2 = mysqli_query($link, $sql2);
        $sqlinsert3 = mysqli_query($link, $sql3);
        $sqlinsert4 = mysqli_query($link, $sql4);
    }

    function getShipHullSize($link, $id) {
        $sqlget = 'SELECT hullsize FROM ships WHERE ID = ' . $id;
        $sqldata = mysqli_fetch_array(mysqli_query($link, $sqlget), MYSQLI_ASSOC);
        return $sqldata['hullsize'];
    }

    function getShipType($link, $id) {
        $sqlget = 'SELECT type FROM ships WHERE ID = ' . $id;
        $sqldata = mysqli_fetch_array(mysqli_query($link, $sqlget), MYSQLI_ASSOC);
        return $sqldata['type'];
    }

    function getShipStrength($link, $id) {
        $sqlget = 'SELECT hullstrength FROM ships WHERE ID = ' . $id;
        $sqldata = mysqli_fetch_array(mysqli_query($link, $sqlget), MYSQLI_ASSOC);
        return $sqldata['hullstrength'];
    }

    function getShipMorale($link, $id) {
        $sqlget = 'SELECT morale FROM ships WHERE ID = ' . $id;
        $sqldata = mysqli_fetch_array(mysqli_query($link, $sqlget), MYSQLI_ASSOC);
        return $sqldata['morale'];
    }

    function getShipWeapons($link, $id) {
        $sqlget = 'SELECT weapons FROM ships WHERE ID = ' . $id;
        $sqldata = mysqli_fetch_array(mysqli_query($link, $sqlget), MYSQLI_ASSOC);
        return $sqldata['weapons'];
    }

    function damageShipHull($link, $id, $damage) {
        $sqlget = 'UPDATE ships SET hullstrength -= ' . $damage . ' WHERE ID = ' . $id;
        $sqldata = mysqli_query($link, $sqlget);
    }

    function damageShipMorale($link, $id, $damage) {
        $sqlget = 'UPDATE ships SET morale -= ' . $damage . ' WHERE ID = ' . $id;
        $sqldata = mysqli_query($link, $sqlget);
    }
    
    function averageNavyMorale($link, $navyid) {
        $sqlget = 'SELECT morale FROM ships WHERE navy = ' . $navyid;
        $sqldata = mysqli_query($link, $sqlget);
        $m = 0;
        $i = 0;
        while($ship = mysqli_fetch_array($sqlnationlist, MYSQLI_ASSOC)) {
            $m += $ship['morale'];
            $i += 1;
        }
        return $m / $i;
    }

    /******************/
    //Leader Functions//
    /******************/

    function getLeaderOfArmy($link, $navyid) {
        $sqlget = 'SELECT leader FROM armies WHERE ID = ' . $id;
        $sqldata = mysqli_fetch_array(mysqli_query($link, $sqlget), MYSQLI_ASSOC);
        return $sqldata['leader'];
    }

    function getAdmiralOfNavy($link, $navyid) {
        $sqlget = 'SELECT leader FROM navies WHERE ID = ' . $id;
        $sqldata = mysqli_fetch_array(mysqli_query($link, $sqlget), MYSQLI_ASSOC);
        return $sqldata['leader'];
    }

    function getLeaderFireSkill($link, $leaderid) {
        $sqlget = 'SELECT fire FROM milleaders WHERE ID = ' . $id;
        $sqldata = mysqli_fetch_array(mysqli_query($link, $sqlget), MYSQLI_ASSOC);
        return $sqldata['fire'];
    }

    function getLeaderShockSkill($link, $leaderid) {
        $sqlget = 'SELECT shock FROM milleaders WHERE ID = ' . $id;
        $sqldata = mysqli_fetch_array(mysqli_query($link, $sqlget), MYSQLI_ASSOC);
        return $sqldata['shock'];
    }

    function getLeaderManeuverSkill($link, $leaderid) {
        $sqlget = 'SELECT maneuver FROM milleaders WHERE ID = ' . $id;
        $sqldata = mysqli_fetch_array(mysqli_query($link, $sqlget), MYSQLI_ASSOC);
        return $sqldata['maneuver'];
    }

    function getLeaderSiegeSkill($link, $leaderid) {
        $sqlget = 'SELECT maneuver FROM milleaders WHERE ID = ' . $id;
        $sqldata = mysqli_fetch_array(mysqli_query($link, $sqlget), MYSQLI_ASSOC);
        return $sqldata['maneuver'];
    }

    /*****************/
    //Admin Functions//
    /*****************/
    function setStartingCash($link) {
        $sqlgetnationlist = 'SELECT ID FROM house';
        $sqlnationlist = mysqli_query($link, $sqlgetnationlist);
        while($house = mysqli_fetch_array($sqlnationlist, MYSQLI_ASSOC)) {
            $income = getNationIncomeByID($link, $house['ID']);
            $starting = $income * 24;
            setHouseCash($link, $house['ID'], $starting);
        }
    }

    function setStartingManpower($link) {
        $sqlgetnationlist = 'SELECT ID FROM house';
        $sqlnationlist = mysqli_query($link, $sqlgetnationlist);
        while($house = mysqli_fetch_array($sqlnationlist, MYSQLI_ASSOC)) {
            $manpower = getNationMaxManpowerByID($link, $house['ID']);
            setHouseManpower($link, $house['ID'], $manpower);
        } 
    }

    function setStartingSailors($link) {
        $sqlgetnationlist = 'SELECT ID FROM house';
        $sqlnationlist = mysqli_query($link, $sqlgetnationlist);
        while($house = mysqli_fetch_array($sqlnationlist, MYSQLI_ASSOC)) {
            $manpower = getNationMaxSailorsByID($link, $house['ID']);
            setHouseSailors($link, $house['ID'], $manpower);
        } 
    }

    function resetMerchants($link) {
        mysqli_query($link, 'TRUNCATE TABLE merchants');
        $sqlgetnationlist = 'SELECT ID, homenode FROM house';
        $sqlnationlist = mysqli_query($link, $sqlgetnationlist);
        while($house = mysqli_fetch_array($sqlnationlist, MYSQLI_ASSOC)) {
            //INSERT Merchant 1 to collect trade in home node
            $sqlmerchants = 'INSERT INTO merchants (house, node, transferringNode) VALUES (' . $house['ID'] . ', ' . $house['homenode'] . ', 0)';
            mysqli_query($link, $sqlmerchants);

            //Get upstream nodes of the homenode
            $sqlgetnodes = 'SELECT upstream FROM tradenodes WHERE ID = ' . $house['homenode'];
            $sqlnodelist = mysqli_fetch_array(mysqli_query($link, $sqlgetnodes), MYSQLI_ASSOC);
            $nodes = explode(",", $sqlnodelist['upstream']);
            if($sqlnodelist['upstream'] != "") {
                $hn = getHighestValueNode($link, $nodes);
                $sqlmerchants = 'INSERT INTO merchants (house, node, transferringNode) VALUES (' . $house['ID'] . ', ' . $hn . ', ' . $house['homenode'] . ')';
                mysqli_query($link, $sqlmerchants);
            } else {
                $sqlmerchants = 'INSERT INTO merchants (house, node, transferringNode) VALUES (' . $house['ID'] . ', ' . $house['homenode'] . ', 0)';
                mysqli_query($link, $sqlmerchants);
            }
        } 
    }

    function getHighestValueNode($link, $nodes) {
        $values = array();
        foreach($nodes as $node) {
            $nv = getTradeNodeValueByID($link, $node);
            array_push($values, $nv);
        }
        $hv = array_keys($values, max($values));
        return $nodes[$hv[0]];
    }

    /***************/
    //Map Functions//
    /***************/
    function generateMap($link) {
        $sqlget = 'SELECT ID, name, house, shape, culture FROM provinces';
        $sqldata2 = mysqli_query($link, $sqlget);
        echo "<svg width=\"70%\" xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"220 395 1000 1480\">";
            while($province = mysqli_fetch_array($sqldata2, MYSQLI_ASSOC)) {
                $color;
                switch ($province['culture']) {
                    case 1:
                        $color = "#0d6000";
                        break;
                    case 2:
                        $color = "#be1e78";
                        break;
                    case 3:
                        $color = "#af0000";
                        break;
                    case 4:
                        $color = "blue";
                        break;
                    case 5:
                        $color = "#d09d00";
                        break;
                    case 6:
                        $color = "#16bfff";
                        break;
                    case 7:
                        $color = "#d77300";
                        break;
                    case 8:
                        $color = "#fafafa";
                        break;
                    case 9:
                        $color = "#47002b";
                        break;
                    case 10:
                        $color = "#00ada8";
                        break;
                    case 11:
                        $color = "#0cc75d";
                        break;
                    case 12:
                        $color = "#7c9999";
                        break;
                    case 13:
                        $color = "#bf0a95";
                        break;
                    case 14:
                        $color = "#3f4f6b";
                        break;
                    case 15:
                        $color = "#858585";
                        break;
                }
                if($province != NULL || $province != "") {
                    $name = getHouseNameByID($link, $province['house']);
                    echo "<a xlink:href = \"province.php?ID=" . $province['ID'] . "\">";
                    echo "<path " . $province['shape'] . "stroke=\"black\" fill=\"". $color . "\" stroke-width=\"3\" \"/>";
                    echo "</a>";
                    echo "<g class=\"tooltip css\" transform=\"translate(1000,450)\">";
                    echo    "<rect x=\"-3em\" y=\"-45\" width=\"200px\" height=\"200px\"/>";
                    echo    "<image width=100px xlink:href=\"img/House_" . $name . ".svg\" />";
                    echo    "<text x=\"50px\" y=\"-45\" dy=\"1em\" text-anchor=\"middle\">" . $province['name'] . "</text>";
                    echo    "<text x=\"50px\" y=\"120px\" dy=\"1em\" text-anchor=\"middle\">House " . $name . "</text>";
                    echo "</g>";
                }
            }
        echo "<path d=\"M1042.48,1761.83c-1.15,0-2.31-.08-3.45,0-1.38.12-2-.51-2.5-1.73s.38-1.77,1-2.41c3.49-3.68,7.87-3.59,12.36-2.77,1,.18,2.1,1,1.76,2-.73,2.11-1.55,4.46-4.23,4.84a35.23,35.23,0,0,1-4.93,0Z\" stroke=\"black\" fill=\"#bf0a95\" stroke-width=\"3\"/>";
        echo "<path d=\"M1030.65,1757.9c1.85-.11,2.82.59,3.1,2.06s-.71,1.83-2,1.89c-1.55.06-3,0-3.23-1.92S1030.39,1758.86,1030.65,1757.9Z\" stroke=\"black\" fill=\"#bf0a95\" stroke-width=\"3\"/>";
        echo "<path d=\"M1044.16,1801.83c-1-.36-2.76.81-3.2-1.13-.35-1.52.86-2.11,2-3,3.45-2.63,4.69-6.73,5.78-10.64.61-2.16,1.36-3.61,3.49-4.21,2.92-.83,2.47,1.9,3.28,3.22.6,1,.86,2.27,2.06,2.63s1.42,1.34,1.17,2.31C1058,1794,1047.33,1801.85,1044.16,1801.83Z\" stroke=\"black\" fill=\"#bf0a95\" stroke-width=\"3\"/>";
        echo "</svg>";
    }
    
?>