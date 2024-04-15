<?php

namespace AmoCRM\Filters;

use AmoCRM\Filters\Interfaces\HasOrderInterface;
use AmoCRM\Filters\Interfaces\HasPagesInterface;
use AmoCRM\Filters\Traits\ArrayOrNumericFilterTrait;
use AmoCRM\Filters\Traits\ArrayOrStringFilterTrait;
use AmoCRM\Filters\Traits\OrderTrait;
use AmoCRM\Filters\Traits\PagesFilterTrait;
use AmoCRM\Filters\Traits\IntOrIntRangeFilterTrait;
use TypeError;

use function array_filter;
use function array_map;
use function array_unique;
use function is_array;
use function is_numeric;

class LeadsFilter extends BaseEntityFilter implements HasPagesInterface, HasOrderInterface
{
    use OrderTrait;
    use PagesFilterTrait;
    use ArrayOrNumericFilterTrait;
    use ArrayOrStringFilterTrait;
    use IntOrIntRangeFilterTrait;

    /**
     * @var array|null
     */
    private $ids = null;

    /**
     * @var array|string|null
     */
    private $names = null;

    /**
     * @var array|int|null
     */
    private $price = null;

    /**
     * @var null|array
     */
    private $createdBy = null;

    /**
     * @var null|array
     */
    private $updatedBy = null;

    /**
     * @var int|array|null
     */
    private $responsibleUserId = null;

    /**
     * @var null|array|int
     */
    private $createdAt = null;

    /**
     * @var null|array|int
     */
    private $updatedAt = null;

    /**
     * @var null|array|int
     */
    private $closedAt = null;

    /**
     * @var int|null|array
     */
    private $closestTaskAt = null;

    /**
     * @var array|null
     */
    private $statuses = null;

    /**
     * @var array|null
     */
    private $pipelineIds = null;

    /**
     * @var null|array
     */
    private $customFieldsValues = null;

    /**
     * @var string|null
     */
    private $query = null;

    /**
     * @return array|null
     */
    public function getIds()
    {
        return $this->ids;
    }

    /**
     * @param array|int $ids
     *
     * @return LeadsFilter
     */
    public function setIds($ids)
    {
        $this->ids = $this->parseArrayOrNumberFilter($ids);

        return $this;
    }

    /**
     * @return array|string|null
     */
    public function getNames()
    {
        return $this->names;
    }

    /**
     * @param array|string|null $names
     *
     * @return LeadsFilter
     */
    public function setNames($names)
    {
        $this->names = $this->parseArrayOrStringFilter($names);

        return $this;
    }

    /**
     * @return array|int|null
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param BaseRangeFilter|int|null $price
     *
     * @return LeadsFilter
     */
    public function setPrice($price)
    {
        $this->price = $this->parseIntOrIntRangeFilter($price);

        return $this;
    }

    /**
     * @return array|null
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * @param array|int $createdBy
     *
     * @return LeadsFilter
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $this->parseArrayOrNumberFilter($createdBy);

        return $this;
    }

    /**
     * @return array|null
     */
    public function getUpdatedBy()
    {
        return $this->updatedBy;
    }

    /**
     * @param array|null $updatedBy
     *
     * @return LeadsFilter
     */
    public function setUpdatedBy($updatedBy)
    {
        $this->updatedBy = $this->parseArrayOrNumberFilter($updatedBy);

        return $this;
    }

    /**
     * @return array|int|null
     */
    public function getResponsibleUserId()
    {
        return $this->responsibleUserId;
    }

    /**
     * @param array|int|null $responsibleUserId
     *
     * @return LeadsFilter
     */
    public function setResponsibleUserId($responsibleUserId)
    {
        $this->responsibleUserId = $this->parseArrayOrNumberFilter($responsibleUserId);

        return $this;
    }

    /**
     * @return array|int|null
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param BaseRangeFilter|int|null $createdAt
     *
     * @return LeadsFilter
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $this->parseIntOrIntRangeFilter($createdAt);

        return $this;
    }

    /**
     * @return array|int|null
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param BaseRangeFilter|int|null $updatedAt
     *
     * @return LeadsFilter
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $this->parseIntOrIntRangeFilter($updatedAt);

        return $this;
    }

    /**
     * @return array|int|null
     */
    public function getClosedAt()
    {
        return $this->closedAt;
    }

    /**
     * @param BaseRangeFilter|int|null $closedAt
     *
     * @return LeadsFilter
     */
    public function setClosedAt($closedAt)
    {
        $this->closedAt = $this->parseIntOrIntRangeFilter($closedAt);

        return $this;
    }

    /**
     * @return array|int|null
     */
    public function getClosestTaskAt()
    {
        return $this->closestTaskAt;
    }

    /**
     * @param BaseRangeFilter|int|null $closestTaskAt
     *
     * @return LeadsFilter
     */
    public function setClosestTaskAt($closestTaskAt)
    {
        $this->closestTaskAt = $this->parseIntOrIntRangeFilter($closestTaskAt);

        return $this;
    }

    /**
     * @return array|null
     */
    public function getStatuses(): ?array
    {
        return $this->statuses;
    }

    /**
     * @param array|null $statuses
     *
     * @return LeadsFilter
     */
    public function setStatuses(?array $statuses): LeadsFilter
    {
        $statusesFilter = [];

        foreach ($statuses as $status) {
            if (!isset($status['status_id'], $status['pipeline_id'])) {
                continue;
            }

            $statusesFilter[] = [
                'status_id' => !empty($status['status_id']) ? (int)$status['status_id'] : null,
                'pipeline_id' => !empty($status['pipeline_id']) ? (int)$status['pipeline_id'] : null,
            ];
        }

        $this->statuses = empty($statusesFilter) ? null : $statusesFilter;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getPipelineIds(): ?array
    {
        return $this->pipelineIds;
    }

    /**
     * @param array|int|null $pipelineIds
     *
     * @return LeadsFilter
     */
    public function setPipelineIds($pipelineIds): LeadsFilter
    {
        if (!is_array($pipelineIds)) {
            $pipelineIds = [$pipelineIds];
        }

        $pipelineIds = array_unique(
            array_filter(
                array_map(
                    function ($val) {
                        return is_numeric($val) && $val > 0 ? (int)$val : null;
                    },
                    $pipelineIds
                )
            )
        );

        $this->pipelineIds = empty($pipelineIds) ? null : $pipelineIds;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getCustomFieldsValues(): ?array
    {
        return $this->customFieldsValues;
    }

    /**
     * @param array|null $customFieldsValues
     *
     * @return LeadsFilter
     */
    public function setCustomFieldsValues(?array $customFieldsValues): LeadsFilter
    {
        $cfFilter = [];

        foreach ($customFieldsValues as $fieldId => $customFieldsValue) {
            if ($customFieldsValue instanceof BaseRangeFilter) {
                $cfFilter[$fieldId] = $customFieldsValue->toFilter();
            } else {
                $cfFilter[$fieldId][] = $customFieldsValue;
            }
        }

        $this->customFieldsValues = $cfFilter;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getQuery(): ?string
    {
        return $this->query;
    }

    /**
     * @param string|int|null $query
     *
     * @return LeadsFilter
     */
    public function setQuery($query): self
    {
        if (!is_string($query) && !is_numeric($query)) {
            throw new TypeError('Invalid query type');
        }

        if (!empty($query)) {
            $this->query = (string)$query;
        }

        return $this;
    }

    /**
     * @return array
     */
    public function buildFilter(): array
    {
        $filter = [];

        if (!is_null($this->getIds())) {
            $filter['filter']['id'] = $this->getIds();
        }

        if (!is_null($this->getNames())) {
            $filter['filter']['name'] = $this->getNames();
        }

        if (!is_null($this->getPrice())) {
            $filter['filter']['price'] = $this->getPrice();
        }

        if (!is_null($this->getCreatedBy())) {
            $filter['filter']['created_by'] = $this->getCreatedBy();
        }

        if (!is_null($this->getUpdatedBy())) {
            $filter['filter']['updated_by'] = $this->getUpdatedBy();
        }

        if (!is_null($this->getResponsibleUserId())) {
            $filter['filter']['responsible_user_id'] = $this->getResponsibleUserId();
        }

        if (!is_null($this->getCreatedAt())) {
            $filter['filter']['created_at'] = $this->getCreatedAt();
        }

        if (!is_null($this->getUpdatedAt())) {
            $filter['filter']['updated_at'] = $this->getUpdatedAt();
        }

        if (!is_null($this->getClosedAt())) {
            $filter['filter']['closed_at'] = $this->getClosedAt();
        }

        if (!is_null($this->getClosestTaskAt())) {
            $filter['filter']['closest_task_at'] = $this->getClosestTaskAt();
        }

        if (!is_null($this->getCustomFieldsValues())) {
            $filter['filter']['custom_fields_values'] = $this->getCustomFieldsValues();
        }

        if (!is_null($this->getStatuses())) {
            $filter['filter']['statuses'] = $this->getStatuses();
        }

        if (!is_null($this->getPipelineIds())) {
            $filter['filter']['pipeline_id'] = $this->getPipelineIds();
        }

        if (!is_null($this->getQuery())) {
            $filter['query'] = $this->getQuery();
        }

        if (!is_null($this->getOrder())) {
            $filter['order'] = $this->getOrder();
        }

        return $this->buildPagesFilter($filter);
    }
}
