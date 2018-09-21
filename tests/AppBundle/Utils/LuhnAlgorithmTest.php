<?php

namespace AppBundle\Utils;

use PHPUnit\Framework\TestCase;

class LuhnAlgorithmTest extends TestCase
{

    /**
     * Generated 50 credit cards from MasterCard
     * http://www.getcreditcardnumbers.com/
     */
    public function testLuhnCalculateCheckDigit()
    {
        self::assertEquals('4', LuhnAlgorithm::calculateCheckDigit('555670990218018'));
        self::assertEquals('3', LuhnAlgorithm::calculateCheckDigit('528923312946584'));
        self::assertEquals('0', LuhnAlgorithm::calculateCheckDigit('534292463984026'));
        self::assertEquals('3', LuhnAlgorithm::calculateCheckDigit('534161958993792'));
        self::assertEquals('0', LuhnAlgorithm::calculateCheckDigit('547889372605688'));
        self::assertEquals('0', LuhnAlgorithm::calculateCheckDigit('544026665273490'));
        self::assertEquals('9', LuhnAlgorithm::calculateCheckDigit('532674285984743'));
        self::assertEquals('4', LuhnAlgorithm::calculateCheckDigit('537640291172378'));
        self::assertEquals('5', LuhnAlgorithm::calculateCheckDigit('518868437741856'));
        self::assertEquals('1', LuhnAlgorithm::calculateCheckDigit('548388919369620'));
        self::assertEquals('0', LuhnAlgorithm::calculateCheckDigit('549563422207478'));
        self::assertEquals('4', LuhnAlgorithm::calculateCheckDigit('532584866592383'));
        self::assertEquals('6', LuhnAlgorithm::calculateCheckDigit('531732216267431'));
        self::assertEquals('3', LuhnAlgorithm::calculateCheckDigit('552519991113677'));
        self::assertEquals('0', LuhnAlgorithm::calculateCheckDigit('527989113773271'));
        self::assertEquals('4', LuhnAlgorithm::calculateCheckDigit('541968461596619'));
        self::assertEquals('2', LuhnAlgorithm::calculateCheckDigit('539144094206961'));
        self::assertEquals('3', LuhnAlgorithm::calculateCheckDigit('526374927506746'));
        self::assertEquals('6', LuhnAlgorithm::calculateCheckDigit('526296218769142'));
        self::assertEquals('9', LuhnAlgorithm::calculateCheckDigit('559685572092899'));
        self::assertEquals('2', LuhnAlgorithm::calculateCheckDigit('551869981846453'));
        self::assertEquals('0', LuhnAlgorithm::calculateCheckDigit('559196651061012'));
        self::assertEquals('2', LuhnAlgorithm::calculateCheckDigit('541624305451417'));
        self::assertEquals('1', LuhnAlgorithm::calculateCheckDigit('551730387715781'));
        self::assertEquals('2', LuhnAlgorithm::calculateCheckDigit('512779306217755'));
        self::assertEquals('7', LuhnAlgorithm::calculateCheckDigit('526834281371654'));
        self::assertEquals('7', LuhnAlgorithm::calculateCheckDigit('519787033986137'));
        self::assertEquals('7', LuhnAlgorithm::calculateCheckDigit('541551564854246'));
        self::assertEquals('0', LuhnAlgorithm::calculateCheckDigit('512404579516871'));
        self::assertEquals('4', LuhnAlgorithm::calculateCheckDigit('533727598998559'));
        self::assertEquals('3', LuhnAlgorithm::calculateCheckDigit('543294879488641'));
        self::assertEquals('7', LuhnAlgorithm::calculateCheckDigit('547881003518404'));
        self::assertEquals('2', LuhnAlgorithm::calculateCheckDigit('548821283435707'));
        self::assertEquals('4', LuhnAlgorithm::calculateCheckDigit('510250728401341'));
        self::assertEquals('3', LuhnAlgorithm::calculateCheckDigit('510036347519586'));
        self::assertEquals('1', LuhnAlgorithm::calculateCheckDigit('538402271889314'));
        self::assertEquals('2', LuhnAlgorithm::calculateCheckDigit('519601351043003'));
        self::assertEquals('3', LuhnAlgorithm::calculateCheckDigit('512047420966023'));
        self::assertEquals('6', LuhnAlgorithm::calculateCheckDigit('545839094060564'));
        self::assertEquals('2', LuhnAlgorithm::calculateCheckDigit('518305882496214'));
        self::assertEquals('5', LuhnAlgorithm::calculateCheckDigit('520680032194037'));
        self::assertEquals('3', LuhnAlgorithm::calculateCheckDigit('528514417590198'));
        self::assertEquals('2', LuhnAlgorithm::calculateCheckDigit('548341821621586'));
        self::assertEquals('6', LuhnAlgorithm::calculateCheckDigit('522727459720574'));
        self::assertEquals('2', LuhnAlgorithm::calculateCheckDigit('555199326574254'));
        self::assertEquals('6', LuhnAlgorithm::calculateCheckDigit('539221634779410'));
        self::assertEquals('9', LuhnAlgorithm::calculateCheckDigit('520735900873347'));
        self::assertEquals('4', LuhnAlgorithm::calculateCheckDigit('551804103618478'));
        self::assertEquals('4', LuhnAlgorithm::calculateCheckDigit('517985871793087'));
        self::assertEquals('4', LuhnAlgorithm::calculateCheckDigit('558849405063563'));
    }
}