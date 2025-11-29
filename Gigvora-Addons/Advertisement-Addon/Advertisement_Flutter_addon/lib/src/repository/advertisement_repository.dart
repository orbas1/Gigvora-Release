import 'package:collection/collection.dart';

import '../api/advertisement_api_client.dart';
import '../models/models.dart';

class AdvertisementRepository {
  AdvertisementRepository({required AdvertisementApiClient api}) : _api = api;

  final AdvertisementApiClient _api;

  Future<List<Campaign>> campaigns() => _api.fetchCampaigns();
  Future<Campaign> createCampaign(Campaign campaign) =>
      _api.createCampaign(campaign.toJson()..remove('id')..remove('ad_groups'));
  Future<Campaign> updateCampaign(Campaign campaign) => _api.updateCampaign(campaign.id, campaign.toJson());

  Future<List<Creative>> creatives({int? adGroupId, int? campaignId}) =>
      _api.fetchCreatives(adGroupId: adGroupId, campaignId: campaignId);
  Future<Creative> createCreative(Creative creative) =>
      _api.createCreative(creative.toJson()..remove('id'));
  Future<Creative> updateCreative(Creative creative) => _api.updateCreative(creative.id, creative.toJson());

  Future<List<Metric>> metrics(int campaignId, DateTime start, DateTime end) =>
      _api.fetchMetrics(campaignId: campaignId, start: start, end: end);

  Future<Forecast> forecast(Forecast draft) => _api.createForecast(draft.campaignId, draft.toJson());
  Future<List<KeywordPrice>> keywordPrices(List<String> keywords) => _api.keywordPrices(keywords);

  Future<List<AffiliateReferral>> referrals() => _api.referrals();
  Future<AffiliateReferral> createReferral(AffiliateReferral referral) =>
      _api.createReferral(referral.toJson()..remove('id'));
  Future<List<AffiliatePayout>> payouts() => _api.payouts();

  Map<String, num> aggregateSpendByPlacement(List<Campaign> campaigns) {
    final totals = <String, num>{};
    for (final campaign in campaigns) {
      for (final adGroup in campaign.adGroups) {
        for (final placement in adGroup.placements) {
          totals.update(placement.type, (value) => value + adGroup.bidAmount,
              ifAbsent: () => adGroup.bidAmount);
        }
      }
    }
    return totals;
  }

  Metric? latestMetric(List<Metric> metrics) => metrics.sortedBy((m) => m.rangeEnd).lastOrNull;
}
