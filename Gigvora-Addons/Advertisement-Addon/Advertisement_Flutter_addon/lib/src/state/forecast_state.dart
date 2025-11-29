import 'package:flutter_bloc/flutter_bloc.dart';

import '../models/models.dart';
import '../services/ads_service.dart';

enum ForecastStatus { idle, loading, loaded, error }

class ForecastState {
  const ForecastState({
    this.status = ForecastStatus.idle,
    this.forecast,
    this.error,
  });

  final ForecastStatus status;
  final Forecast? forecast;
  final String? error;

  ForecastState copyWith({
    ForecastStatus? status,
    Forecast? forecast,
    String? error,
  }) {
    return ForecastState(
      status: status ?? this.status,
      forecast: forecast ?? this.forecast,
      error: error ?? this.error,
    );
  }
}

class ForecastCubit extends Cubit<ForecastState> {
  ForecastCubit(this.service) : super(const ForecastState());

  final AdsService service;

  Future<void> fetch(int campaignId, Map<String, dynamic> payload) async {
    emit(state.copyWith(status: ForecastStatus.loading));
    try {
      final forecast = await service.fetchForecast(campaignId, payload);
      emit(state.copyWith(status: ForecastStatus.loaded, forecast: forecast));
    } catch (e) {
      emit(state.copyWith(status: ForecastStatus.error, error: e.toString()));
    }
  }
}
