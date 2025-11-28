import 'package:flutter_bloc/flutter_bloc.dart';

import '../models/models.dart';
import '../services/ads_service.dart';

enum CreativeStatus { idle, loading, loaded, saving, error }

class CreativeState {
  const CreativeState({
    this.status = CreativeStatus.idle,
    this.creatives = const [],
    this.error,
  });

  final CreativeStatus status;
  final List<Creative> creatives;
  final String? error;

  CreativeState copyWith({
    CreativeStatus? status,
    List<Creative>? creatives,
    String? error,
  }) {
    return CreativeState(
      status: status ?? this.status,
      creatives: creatives ?? this.creatives,
      error: error ?? this.error,
    );
  }
}

class CreativeCubit extends Cubit<CreativeState> {
  CreativeCubit(this.service) : super(const CreativeState());

  final AdsService service;

  Future<void> load(int adGroupId) async {
    emit(state.copyWith(status: CreativeStatus.loading));
    try {
      final creatives = await service.fetchCreatives(adGroupId);
      emit(state.copyWith(status: CreativeStatus.loaded, creatives: creatives));
    } catch (e) {
      emit(state.copyWith(status: CreativeStatus.error, error: e.toString()));
    }
  }

  Future<void> save(Map<String, dynamic> payload) async {
    emit(state.copyWith(status: CreativeStatus.saving));
    try {
      await service.createCreative(payload);
      emit(state.copyWith(status: CreativeStatus.loaded));
    } catch (e) {
      emit(state.copyWith(status: CreativeStatus.error, error: e.toString()));
    }
  }
}
