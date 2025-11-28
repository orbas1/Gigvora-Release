part of 'ads_blocs.dart';

class AffiliateState extends Equatable {
  const AffiliateState._({
    this.referrals = const [],
    this.payouts = const [],
    this.status = AffiliateStatus.idle,
    this.error,
  });

  const AffiliateState.idle() : this._();
  const AffiliateState.loading() : this._(status: AffiliateStatus.loading);
  const AffiliateState.ready(List<AffiliateReferral> referrals, List<AffiliatePayout> payouts)
      : this._(status: AffiliateStatus.ready, referrals: referrals, payouts: payouts);
  const AffiliateState.error(String message)
      : this._(status: AffiliateStatus.error, error: message);

  final List<AffiliateReferral> referrals;
  final List<AffiliatePayout> payouts;
  final AffiliateStatus status;
  final String? error;

  @override
  List<Object?> get props => [referrals, payouts, status, error];
}

enum AffiliateStatus { idle, loading, ready, error }
