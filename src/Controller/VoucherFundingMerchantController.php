<?php declare(strict_types=1);

namespace SwagVoucherFunding\Controller;

use Shopware\Core\Checkout\Cart\Exception\CustomerNotLoggedInException;
use Shopware\Core\Framework\Routing\Annotation\RouteScope;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Production\Merchants\Content\Merchant\SalesChannelContextExtension;
use SwagVoucherFunding\Service\VoucherFundingMerchantService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @RouteScope(scopes={"storefront"})
 */
class VoucherFundingMerchantController extends StorefrontController
{
    private $voucherFundingService;

    public function __construct(VoucherFundingMerchantService $voucherFundingService)
    {
        $this->voucherFundingService = $voucherFundingService;
    }

    /**
     * @Route(name="voucher-funding-merchant-api.sold.vouchers.load", path="/merchant-api/v{version}/voucher-funding/sold/vouchers", methods={"GET"})
     * @throws CustomerNotLoggedInException
     */
    public function loadSoldVouchers(SalesChannelContext $context) : JsonResponse
    {
        $merchant = SalesChannelContextExtension::extract($context);

        $soldVouchers = $this->voucherFundingService->loadSoldVouchers($merchant->getId(), $context);

        return new JsonResponse([
            'data' => $soldVouchers
        ]);
    }

    /**
     * @Route("/merchant-api/{version}/voucher/redeem", name="merchant-api.action.voucher-redeem", methods={"POST"})
     * @throws CustomerNotLoggedInException
     */
    public function redeemVoucher(Request $request, SalesChannelContext $context): JsonResponse
    {
        $merchant = SalesChannelContextExtension::extract($context);

        $this->voucherFundingService->redeemVoucher($merchant, $context);

        return new JsonResponse(['code' => 200, 'Content' => 'success']);
    }

}
