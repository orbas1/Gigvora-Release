import 'package:flutter_bloc/flutter_bloc.dart';

import '../models/models.dart';
import '../services/ads_service.dart';

enum CampaignDetailStatus { loading, loaded, error }

class CampaignDetailState {
  const CampaignDetailState({
    this.status = CampaignDetailStatus.loading,
    this.campaign,
    this.metrics = const [],
    this.error,
  });

  final CampaignDetailStatus status;
  final Campaign? campaign;
  final List<Metric> metrics;
  final String? error;

  CampaignDetailState copyWith({
    CampaignDetailStatus? status,
    Campaign? campaign,
    List<Metric>? metrics,
    String? error,
  }) {
    return CampaignDetailState(
      status: status ?? this.status,
      campaign: campaign ?? this.campaign,
      metrics: metrics ?? this.metrics,
      error: error ?? this.error,
    );
  }
}

class CampaignDetailCubit extends Cubit<CampaignDetailState> {
  CampaignDetailCubit(this.service) : super(const CampaignDetailState());

  final AdsService service;

  Future<void> load(Campaign campaign) async {
    emit(state.copyWith(status: CampaignDetailStatus.loading, campaign: campaign));
    try {
      final metrics = await service.fetchMetrics(
        campaignId: campaign.id,
        start: DateTime.now().subtract(const Duration(days: 7)),
        end: DateTime.now(),
      );
      emit(state.copyWith(status: CampaignDetailStatus.loaded, metrics: metrics));
    } catch (e) {
      emit(state.copyWith(status: CampaignDetailStatus.error, error: e.toString()));
    }
  }
}
