part of 'ads_blocs.dart';

class CampaignState extends Equatable {
  const CampaignState._({
    this.campaigns = const [],
    this.status = CampaignStatus.loading,
    this.error,
  });

  const CampaignState.loading() : this._();
  const CampaignState.ready(List<Campaign> campaigns)
      : this._(campaigns: campaigns, status: CampaignStatus.ready);
  const CampaignState.error(String message)
      : this._(status: CampaignStatus.error, error: message);

  final List<Campaign> campaigns;
  final CampaignStatus status;
  final String? error;

  @override
  List<Object?> get props => [campaigns, status, error];
}

enum CampaignStatus { loading, ready, error }
