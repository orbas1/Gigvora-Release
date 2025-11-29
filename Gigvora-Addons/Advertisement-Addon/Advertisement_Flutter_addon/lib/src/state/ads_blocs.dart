import 'package:bloc/bloc.dart';
import 'package:equatable/equatable.dart';

import '../models/models.dart';
import '../repository/advertisement_repository.dart';

part 'campaign_state.dart';
part 'creative_state.dart';
part 'analytics_state.dart';
part 'forecast_state.dart';
part 'keyword_planner_state.dart';
part 'affiliate_state.dart';

class CampaignBloc extends Cubit<CampaignState> {
  CampaignBloc(this.repository) : super(const CampaignState.loading());

  final AdvertisementRepository repository;

  Future<void> load() async {
    emit(const CampaignState.loading());
    try {
      final campaigns = await repository.campaigns();
      emit(CampaignState.ready(campaigns));
    } catch (e) {
      emit(CampaignState.error(e.toString()));
    }
  }

  Future<void> save(Campaign campaign) async {
    emit(const CampaignState.loading());
    try {
      if (campaign.id == 0) {
        await repository.createCampaign(campaign);
      } else {
        await repository.updateCampaign(campaign);
      }
      await load();
    } catch (e) {
      emit(CampaignState.error(e.toString()));
    }
  }
}

class CreativeBloc extends Cubit<CreativeState> {
  CreativeBloc(this.repository) : super(const CreativeState.idle());

  final AdvertisementRepository repository;

  Future<void> load(int adGroupId) async {
    emit(const CreativeState.loading());
    try {
      final creatives = await repository.creatives(adGroupId: adGroupId);
      emit(CreativeState.ready(creatives));
    } catch (e) {
      emit(CreativeState.error(e.toString()));
    }
  }

  Future<void> save(Creative creative) async {
    emit(const CreativeState.loading());
    try {
      if (creative.id == 0) {
        await repository.createCreative(creative);
      } else {
        await repository.updateCreative(creative);
      }
      await load(creative.adGroupId ?? 0);
    } catch (e) {
      emit(CreativeState.error(e.toString()));
    }
  }
}

class AnalyticsBloc extends Cubit<AnalyticsState> {
  AnalyticsBloc(this.repository) : super(const AnalyticsState.idle());

  final AdvertisementRepository repository;

  Future<void> load({required int campaignId, required DateTime start, required DateTime end}) async {
    emit(const AnalyticsState.loading());
    try {
      final metrics = await repository.metrics(campaignId, start, end);
      emit(AnalyticsState.ready(metrics));
    } catch (e) {
      emit(AnalyticsState.error(e.toString()));
    }
  }
}

class ForecastBloc extends Cubit<ForecastState> {
  ForecastBloc(this.repository) : super(const ForecastState.idle());

  final AdvertisementRepository repository;

  Future<void> run(Forecast draft) async {
    emit(const ForecastState.loading());
    try {
      final result = await repository.forecast(draft);
      emit(ForecastState.ready(result));
    } catch (e) {
      emit(ForecastState.error(e.toString()));
    }
  }
}

class KeywordPlannerBloc extends Cubit<KeywordPlannerState> {
  KeywordPlannerBloc(this.repository) : super(const KeywordPlannerState.idle());

  final AdvertisementRepository repository;

  Future<void> search(String keyword) async {
    emit(const KeywordPlannerState.loading());
    try {
      final prices = await repository.keywordPrices([keyword]);
      emit(KeywordPlannerState.ready(keyword, prices));
    } catch (e) {
      emit(KeywordPlannerState.error(e.toString()));
    }
  }
}

class AffiliateBloc extends Cubit<AffiliateState> {
  AffiliateBloc(this.repository) : super(const AffiliateState.idle());

  final AdvertisementRepository repository;

  Future<void> refresh() async {
    emit(const AffiliateState.loading());
    try {
      final referrals = await repository.referrals();
      final payouts = await repository.payouts();
      emit(AffiliateState.ready(referrals, payouts));
    } catch (e) {
      emit(AffiliateState.error(e.toString()));
    }
  }

  Future<void> createReferral(AffiliateReferral referral) async {
    emit(const AffiliateState.loading());
    try {
      await repository.createReferral(referral);
      await refresh();
    } catch (e) {
      emit(AffiliateState.error(e.toString()));
    }
  }
}
