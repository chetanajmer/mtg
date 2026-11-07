    // A tier's percentage and price stay in step: the customer pays that percentage of the Cost Price
    function tierCostPrice(){
        return parseFloat($('input[name="price"]').val()) || 0;
    }

    function tierPricesFromPercent(){
        var cost = tierCostPrice();
        $('.tier-row').each(function(){
            var percent = parseFloat($(this).find('.tier-percent').val()) || 0;
            if(cost > 0 && percent > 0){
                $(this).find('input[name="tier_price[]"]').val((cost * percent / 100).toFixed(2)).trigger('change');
            }
        });
    }

    function tierPercentFromPrice($row){
        var cost = tierCostPrice();
        var price = parseFloat($row.find('input[name="tier_price[]"]').val()) || 0;
        $row.find('.tier-percent').val(cost > 0 && price > 0 ? +(price / cost * 100).toFixed(2) : '');
    }

    $(document).on('input', '.tier-percent', function(){
        var $row = $(this).closest('.tier-row');
        var percent = parseFloat($(this).val()) || 0;
        var cost = tierCostPrice();
        if(cost > 0 && percent > 0){
            $row.find('input[name="tier_price[]"]').val((cost * percent / 100).toFixed(2)).trigger('change');
        }
    });

    $(document).on('input', '.tier-row input[name="tier_price[]"]', function(){
        tierPercentFromPrice($(this).closest('.tier-row'));
    });

    $('input[name="price"]').on('input change', tierPricesFromPercent);

    $('.tier-row').each(function(){
        if(parseFloat($(this).find('input[name="tier_price[]"]').val()) > 0){ tierPercentFromPrice($(this)); }
    });

    // Same rules as ProductController::invalidTierPrices(), checked here so a refused save doesn't lose what was typed
    function tierPricingProblem(){
        var tiers = [], problem = null;
        $('#tier_rows .tier-row').each(function(){
            var min = parseInt($(this).find('input[name="tier_min[]"]').val(), 10) || 0;
            var max = parseInt($(this).find('input[name="tier_max[]"]').val(), 10) || 0;
            var price = parseFloat($(this).find('input[name="tier_price[]"]').val()) || 0;
            if(min <= 0 && max <= 0 && price <= 0){ return; }
            if(min <= 0){ problem = 'every quantity tier needs a Min Qty.'; }
            else if(price <= 0){ problem = 'every quantity tier needs a price above 0 (tier starting at ' + min + ' pcs).'; }
            else if(max > 0 && max < min){ problem = 'the tier starting at ' + min + ' pcs has a Max Qty below its Min Qty.'; }
            if(problem){ return false; }
            tiers.push({min: min, max: max, price: price});
        });
        if(problem){ return problem; }
        if(tiers.length === 0){ return 'tier pricing is on but no tiers were entered. Add a tier or turn tier pricing off.'; }

        tiers.sort(function(a, b){ return a.min - b.min; });
        var moq = Math.max(1, parseInt($('input[name="min_order_quantity"]').val(), 10) || 0);
        for(var i = 0; i < tiers.length; i++){
            var tier = tiers[i], next = tiers[i + 1];
            if(tier.max > 0 && tier.max < moq){ return 'the tier ending at ' + tier.max + ' pcs is below the minimum order quantity (' + moq + ') and could never be ordered.'; }
            if(!next){
                if(tier.max > 0){ return 'leave Max Qty blank on the last tier, so orders above ' + tier.max + ' pcs keep the tier price.'; }
                continue;
            }
            if(tier.max <= 0 || next.min <= tier.max){ return 'the tiers starting at ' + tier.min + ' and ' + next.min + ' pcs overlap.'; }
            if(next.min > tier.max + 1){ return 'no tier covers ' + (tier.max + 1) + ' to ' + (next.min - 1) + ' pcs.'; }
            if(next.price > tier.price){ return 'the price goes up at ' + next.min + ' pcs. A larger quantity must not cost more per piece.'; }
        }
        return null;
    }

    $('#choice_form').on('submit', function(e){
        if(!$('input[name="tier_pricing_enabled"]').is(':checked')){ return; }
        var problem = tierPricingProblem();
        if(problem){
            e.preventDefault();
            alert('Tier pricing: ' + problem);
            $('#tier_pricing_body')[0].scrollIntoView({block: 'center'});
        }
    });
