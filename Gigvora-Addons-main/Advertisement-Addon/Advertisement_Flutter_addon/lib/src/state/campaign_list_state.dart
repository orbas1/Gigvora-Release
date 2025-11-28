import 'package:flutter_bloc/flutter_bloc.dart';

import '../models/models.dart';
import '../services/ads_service.dart';

enum CampaignListStatus { loading, loaded, error }

class CampaignListState {
  const CampaignListState({
    this.status = CampaignListStatus.loading,
    this.campaigns = const [],
    this.error,
    this.filter = '',
  });

  final CampaignListStatus status;
  final List<Campaign> campaigns;
  final String? error;
  final String filter;

  CampaignListState copyWith({
    CampaignListStatus? status,
    List<Campaign>? campaigns,
    String? error,
    String? filter,
  }) {
    return CampaignListState(
      status: status ?? this.status,
      campaigns: campaigns ?? this.campaigns,
      error: error ?? this.error,
      filter: filter ?? this.filter,
    );
  }
}

class CampaignListCubit extends Cubit<CampaignListState> {
  CampaignListCubit(this.service) : super(const CampaignListState());

  final AdsService service;

  Future<void> load({String? search}) async {
    emit(state.copyWith(status: CampaignListStatus.loading, filter: search));
    try {
      final campaigns = await service.fetchCampaigns();
      final filtered = search == null || search.isEmpty
          ? campaigns
          : campaigns
              .where((c) => c.name.toLowerCase().contains(search.toLowerCase()))
              .toList();
      emit(state.copyWith(
        status: CampaignListStatus.loaded,
        campaigns: filtered,
      ));
    } catch (e) {
      emit(state.copyWith(status: CampaignListStatus.error, error: e.toString()));
    }
  }
}
