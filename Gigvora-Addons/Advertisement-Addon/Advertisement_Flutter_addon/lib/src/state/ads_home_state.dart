import 'package:flutter_bloc/flutter_bloc.dart';

import '../models/models.dart';
import '../services/ads_service.dart';

class AdsHomeState {
  const AdsHomeState({
    this.status = AdsHomeStatus.loading,
    this.campaigns = const [],
    this.metrics = const {},
    this.error,
  });

  final AdsHomeStatus status;
  final List<Campaign> campaigns;
  final Map<String, dynamic> metrics;
  final String? error;

  AdsHomeState copyWith({
    AdsHomeStatus? status,
    List<Campaign>? campaigns,
    Map<String, dynamic>? metrics,
    String? error,
  }) {
    return AdsHomeState(
      status: status ?? this.status,
      campaigns: campaigns ?? this.campaigns,
      metrics: metrics ?? this.metrics,
      error: error ?? this.error,
    );
  }
}

enum AdsHomeStatus { loading, loaded, error }

class AdsHomeCubit extends Cubit<AdsHomeState> {
  AdsHomeCubit(this.service) : super(const AdsHomeState());

  final AdsService service;

  Future<void> load() async {
    emit(state.copyWith(status: AdsHomeStatus.loading));
    try {
      final campaigns = await service.fetchCampaigns();
      final metrics = {
        'spend': r'$12,400',
        'impressions': '1.2M',
        'clicks': '52K',
        'conversions': '3.2K',
      };
      emit(state.copyWith(
        status: AdsHomeStatus.loaded,
        campaigns: campaigns,
        metrics: metrics,
      ));
    } catch (e) {
        emit(state.copyWith(status: AdsHomeStatus.error, error: e.toString()));
    }
  }
}
